<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * OpenAI-compatible chat client. Works with OpenAI, Groq, OpenRouter, vLLM,
 * Ollama (with /v1 enabled), or anything else that speaks the chat-completions API.
 *
 * Returns the parsed JSON sales-page payload.
 */
class SalesPageGenerator
{
    public function __construct(private ContextManager $context) {}

    /**
     * @param  array<string,mixed>  $input
     * @return array{content: array<string,mixed>, context_summary: string}
     */
    public function generate(array $input, \App\Models\User $user): array
    {
        $bundle = $this->context->buildForUser($user);
        $prompt = $this->buildPrompt($input, $bundle['summary']);

        $payload = $this->callLlm($prompt);
        $content = $this->parseJson($payload);
        $content = $this->validateShape($content);

        $summary = $this->context->summarizePage($input, $content);

        return ['content' => $content, 'context_summary' => $summary];
    }

    private function buildPrompt(array $input, string $contextSummary): array
    {
        $system = <<<'SYS'
You are a senior conversion copywriter.

Your task is to generate a high-converting sales page.

Use the provided CONTEXT to maintain consistency in tone, style, and messaging.

Return ONLY valid JSON. No prose, no markdown fences.

Structure:
{
  "headline": string,
  "subheadline": string,
  "description": string,
  "benefits": string[],
  "features": string[],
  "social_proof": string,
  "price": string,
  "call_to_action": string
}

Rules:
- Follow AIDA principles (Attention, Interest, Desire, Action).
- Maintain consistency with previous outputs (tone, audience framing, positioning).
- Avoid repetition: do not reuse previous headlines or CTAs verbatim.
- Keep it natural and persuasive.
- Language: Bahasa Indonesia.
SYS;

        $user = sprintf(
            "CONTEXT (previous user generations):\n%s\n\nCURRENT INPUT:\nProduct Name: %s\nDescription: %s\nFeatures: %s\nTarget Audience: %s\nPrice: %s\nUSP: %s\nTone: %s\n\nReturn JSON only.",
            $contextSummary,
            (string) ($input['product_name'] ?? ''),
            (string) ($input['description'] ?? ''),
            is_array($input['features'] ?? null) ? implode(', ', $input['features']) : (string) ($input['features'] ?? ''),
            (string) ($input['target_audience'] ?? ''),
            (string) ($input['price'] ?? ''),
            (string) ($input['usp'] ?? ''),
            (string) ($input['tone'] ?? 'profesional & meyakinkan'),
        );

        return [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ];
    }

    private function callLlm(array $messages): string
    {
        $base = rtrim((string) config('services.openai.base_url'), '/');
        $key = (string) config('services.openai.api_key');
        $model = (string) config('services.openai.model');

        if ($key === '' || $base === '') {
            throw new RuntimeException('LLM not configured. Set OPENAI_API_KEY and OPENAI_BASE_URL in .env');
        }

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
            'response_format' => $this->responseFormat(),
        ];

        $response = Http::withToken($key)
            ->timeout((int) config('services.openai.timeout', 60))
            ->acceptJson()
            ->post($base.'/chat/completions', $payload);

        // If the endpoint doesn't support json_schema (older models / some proxies),
        // automatically retry with the broader json_object mode.
        if ($response->status() === 400 && config('services.openai.schema_mode', true)) {
            $body = (string) $response->body();
            if (str_contains(strtolower($body), 'json_schema') || str_contains(strtolower($body), 'response_format')) {
                $payload['response_format'] = ['type' => 'json_object'];
                $response = Http::withToken($key)
                    ->timeout((int) config('services.openai.timeout', 60))
                    ->acceptJson()
                    ->post($base.'/chat/completions', $payload);
            }
        }

        if ($response->failed()) {
            Log::error('LLM call failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new RuntimeException('LLM request failed: '.$response->status());
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        if (! is_string($content) || $content === '') {
            throw new RuntimeException('LLM returned empty content');
        }

        return $content;
    }

    /**
     * Prefer the stricter json_schema mode (gpt-4o-2024-08-06+, Groq, most modern endpoints).
     * Falls back to json_object if OPENAI_SCHEMA_MODE is disabled or the endpoint rejects it.
     */
    private function responseFormat(): array
    {
        if (! config('services.openai.schema_mode', true)) {
            return ['type' => 'json_object'];
        }

        return [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => 'sales_page',
                'strict' => true,
                'schema' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => ['headline', 'subheadline', 'description', 'benefits', 'features', 'social_proof', 'price', 'call_to_action'],
                    'properties' => [
                        'headline' => ['type' => 'string'],
                        'subheadline' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'benefits' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 3, 'maxItems' => 8],
                        'features' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 3, 'maxItems' => 8],
                        'social_proof' => ['type' => 'string'],
                        'price' => ['type' => 'string'],
                        'call_to_action' => ['type' => 'string'],
                    ],
                ],
            ],
        ];
    }

    private function parseJson(string $raw): array
    {
        // Strip markdown code fences if the model added them anyway.
        $cleaned = trim($raw);
        $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $cleaned) ?? $cleaned;

        $decoded = json_decode($cleaned, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('LLM returned non-JSON content: '.\Illuminate\Support\Str::limit($raw, 200));
        }

        return $decoded;
    }

    /**
     * Coerce missing fields to safe defaults so the view never breaks.
     */
    private function validateShape(array $content): array
    {
        return [
            'headline' => (string) ($content['headline'] ?? ''),
            'subheadline' => (string) ($content['subheadline'] ?? ''),
            'description' => (string) ($content['description'] ?? ''),
            'benefits' => array_values(array_filter(array_map('strval', (array) ($content['benefits'] ?? [])))),
            'features' => array_values(array_filter(array_map('strval', (array) ($content['features'] ?? [])))),
            'social_proof' => (string) ($content['social_proof'] ?? ''),
            'price' => (string) ($content['price'] ?? ''),
            'call_to_action' => (string) ($content['call_to_action'] ?? ''),
        ];
    }
}
