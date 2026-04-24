# AI Sales Page Generator

A Laravel 11 web app that generates **high-converting sales pages** with an
**MCP-lite context engine**. Each generation learns from the user's previous
work, so tone, audience framing, and positioning stay consistent — without
repeating headlines or CTAs.

---

## 1 — System Overview

```
 ┌──────────────┐    ┌────────────────────┐    ┌────────────────┐
 │  Blade UI    │ →  │ SalesPageController│ →  │ ContextManager │
 │ (Tailwind)   │    │  + Form Request    │    │   (MCP-lite)   │
 └──────────────┘    └─────────┬──────────┘    └────────┬───────┘
                               ▼                        │ build context
                     ┌────────────────────┐             │
                     │ SalesPageGenerator │ ◄───────────┘
                     │   (LLM client)     │
                     └─────────┬──────────┘
                               │ chat/completions (JSON mode)
                               ▼
                     ┌────────────────────┐
                     │ OpenAI-compatible  │
                     │     endpoint       │
                     └─────────┬──────────┘
                               ▼
                     ┌────────────────────┐
                     │ MySQL: sales_pages │
                     └────────────────────┘
```

**Stack:** Laravel 11 · Blade · Tailwind (CDN) · MySQL 8.4 · PHP 8.3-FPM · Nginx · Docker Compose.

**Auth:** Manual Breeze-style controllers (register / login / logout).

**LLM:** Any OpenAI-compatible chat-completions endpoint
(OpenAI, Groq, OpenRouter, vLLM, Ollama via `/v1`, etc.).

---

## 2 — Context Architecture (MCP-lite)

The Model Context Protocol idea is implemented as a small, focused service:
[`app/Services/ContextManager.php`](app/Services/ContextManager.php).

### 2.1 Storage
Every generation persists to `sales_pages`:

| column              | type | purpose                                  |
|---------------------|------|------------------------------------------|
| `input_data`        | JSON | user-supplied form values                 |
| `generated_content` | JSON | the validated AIDA payload from the LLM   |
| `context_summary`   | TEXT | one-line digest produced at write time    |

Pre-computing the per-page summary at write time keeps **read** cheap — we
don't have to re-summarize history on every new generation.

### 2.2 Retrieval
On every generate request, `ContextManager::buildForUser($user)` loads the
**last 5** pages (configurable) and produces a bundle:

```php
[
  'summary'  => string,   // budgeted text, ready to inject into the prompt
  'patterns' => [
    'dominant_tone'     => 'profesional & meyakinkan',
    'dominant_audience' => 'pemilik UMKM',
    'recent_usps'       => [...],
    'sample_size'       => 5,
  ],
  'avoid'    => [
    'headlines' => [...],   // last 5 headlines — model is told not to repeat
    'ctas'      => [...],   // last 5 CTAs
  ],
  'used'     => 5,
]
```

### 2.3 Injection
The bundle is rendered into a single `CONTEXT (...)` block injected into the
user message. The system message carries the JSON schema and the AIDA /
consistency rules. JSON mode is enforced via
`response_format: { type: "json_object" }`.

### 2.4 Window Optimization
- Hard char budget of **2,400 chars** (≈ 600 tokens), tail-truncated so the
  freshest context survives.
- We intentionally do **not** call another LLM to summarize — instead a
  cheap, deterministic rule-based digest at write time. Faster, cheaper, and
  there's no second model to drift.

### 2.5 The "Context7" Principle
Five guarantees the engine enforces on every generation:

1. **Consistent** — dominant tone & audience are surfaced in the prompt.
2. **Coherent** — recent USPs are echoed so positioning doesn't drift.
3. **Context-aware** — every prior summary is included verbatim.
4. **Not repetitive** — explicit "do not reuse" list of past headlines/CTAs.
5. **Not conflicting** — the create form pre-populates with the dominant
   tone/audience to keep human input aligned too.

---

## 3 — Docker Setup

Three services in `docker-compose.yml`:

| service | image                | role                                   |
|---------|----------------------|----------------------------------------|
| `nginx` | `nginx:1.27-alpine`  | HTTP front, proxies PHP to `app:9000`  |
| `app`   | built from Dockerfile | PHP 8.3-FPM + Laravel + extensions    |
| `mysql` | `mysql:8.4`          | persistent data                        |

The `app` entrypoint (`docker/entrypoint.sh`) handles first-boot:
copies `.env`, generates `APP_KEY`, waits for MySQL, runs `migrate --force`.

```bash
# 1. Configure env
cp .env.example .env
# Edit OPENAI_API_KEY (and OPENAI_BASE_URL / OPENAI_MODEL if needed)

# 2. Boot
docker compose up -d --build

# 3. Open
open http://localhost:8080
```

That's it. No host-side `composer install`, no `npm install` — Tailwind is
loaded via CDN for zero-build simplicity.

### Useful one-liners

```bash
# Tail Laravel logs
docker compose exec app tail -f storage/logs/laravel.log

# Fresh DB
docker compose exec app php artisan migrate:fresh --force

# Tinker
docker compose exec app php artisan tinker

# Stop everything
docker compose down

# Stop and wipe data
docker compose down -v
```

---

## 4 — Laravel Implementation Map

| concern              | file                                                            |
|----------------------|-----------------------------------------------------------------|
| Routes               | `routes/web.php`                                                |
| Auth                 | `app/Http/Controllers/Auth/AuthController.php`                  |
| Dashboard            | `app/Http/Controllers/DashboardController.php`                  |
| Sales pages          | `app/Http/Controllers/SalesPageController.php`                  |
| Form validation      | `app/Http/Requests/StoreSalesPageRequest.php`                   |
| Context engine       | `app/Services/ContextManager.php`                               |
| LLM client           | `app/Services/SalesPageGenerator.php`                           |
| Eloquent model       | `app/Models/SalesPage.php`                                      |
| Migration            | `database/migrations/2026_04_24_000000_create_sales_pages_table.php` |
| Service config       | `config/services.php` (`openai.*`)                              |
| Layout               | `resources/views/layouts/app.blade.php`                         |
| Live preview         | `resources/views/sales-pages/preview.blade.php`                 |

### 4.1 The Prompt
Built in `SalesPageGenerator::buildPrompt()`:

- **System message** — copywriter persona + JSON schema + AIDA rules + Bahasa Indonesia.
- **User message** — `CONTEXT (...)` block from `ContextManager` + the current form input.

The model is forced into JSON mode and the response is shape-validated
(`validateShape()`) before being persisted, so the view layer never breaks
on a missing field.

---

## 5 — AI Integration

`config/services.php`:
```php
'openai' => [
    'api_key'  => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'model'    => env('OPENAI_MODEL', 'gpt-4o-mini'),
    'timeout'  => env('OPENAI_TIMEOUT', 60),
],
```

Anything that speaks the OpenAI chat-completions API works:

| provider     | `OPENAI_BASE_URL`                          | `OPENAI_MODEL` example       |
|--------------|--------------------------------------------|------------------------------|
| OpenAI       | `https://api.openai.com/v1`                | `gpt-4o-mini`                |
| Groq         | `https://api.groq.com/openai/v1`           | `llama-3.3-70b-versatile`    |
| OpenRouter   | `https://openrouter.ai/api/v1`             | `anthropic/claude-3.5-sonnet`|
| Ollama       | `http://host.docker.internal:11434/v1`     | `llama3.1`                   |

---

## 6 — Deployment Guide

### Local / Staging
```bash
cp .env.example .env
# set OPENAI_API_KEY
docker compose up -d --build
```

### Production hardening checklist
1. In `.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - rotate `DB_PASSWORD`, `DB_ROOT_PASSWORD`
   - set `APP_URL=https://yourdomain.com`
2. Put a TLS terminator in front of `nginx` (Caddy, Traefik, or a managed LB).
3. Mount a persistent volume for `mysql_data` (already declared in compose).
4. Schedule daily DB backups: `docker compose exec mysql mysqldump …`.
5. Set log rotation on the host for the bind-mounted `storage/logs/`.
6. Restrict `DB_PORT_FORWARD` (`3307`) — comment it out if MySQL doesn't
   need to be reachable from the host.

### Updates
```bash
git pull
docker compose build app
docker compose up -d
# the entrypoint will re-run pending migrations automatically
```

---

## 7 — Routes

| method | path                          | name                      |
|--------|-------------------------------|---------------------------|
| GET    | `/`                           | `home`                    |
| GET    | `/register`                   | `register`                |
| POST   | `/register`                   | —                         |
| GET    | `/login`                      | `login`                   |
| POST   | `/login`                      | —                         |
| POST   | `/logout`                     | `logout`                  |
| GET    | `/dashboard`                  | `dashboard`               |
| GET    | `/sales-pages`                | `sales-pages.index`       |
| GET    | `/sales-pages/create`         | `sales-pages.create`      |
| POST   | `/sales-pages`                | `sales-pages.store`       |
| GET    | `/sales-pages/{salesPage}`    | `sales-pages.show`        |
| GET    | `/sales-pages/{salesPage}/preview` | `sales-pages.preview` |
| DELETE | `/sales-pages/{salesPage}`    | `sales-pages.destroy`     |

---

## 8 — License

MIT.
# ai-generator-page
