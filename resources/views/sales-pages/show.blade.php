@extends('layouts.app')
@section('title', $page->product_name)

@section('content')
@php $g = $page->generated_content; @endphp

<div x-data="{ copied:'', copy(key,val){ navigator.clipboard.writeText(val); this.copied = key; setTimeout(()=>this.copied='', 1500) } }">

    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <nav class="text-xs text-slate-500">
                <a href="{{ route('sales-pages.index') }}" class="hover:underline">Riwayat</a>
                <span class="mx-1">/</span>
                <span class="text-slate-700">{{ $page->product_name }}</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight">{{ $page->product_name }}</h1>
            <p class="mt-1 text-xs text-slate-500">Dibuat {{ $page->created_at->diffForHumans() }} · {{ $page->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="copy('all', @js(json_encode($g, JSON_UNESCAPED_UNICODE)))"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                <span x-text="copied==='all' ? 'Tersalin!' : 'Copy JSON'"></span>
            </button>
            <a href="{{ route('sales-pages.preview', $page) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                Preview Penuh
            </a>
            <a href="{{ route('sales-pages.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 px-3 py-2 text-sm font-medium text-white hover:from-brand-700 hover:to-brand-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Generate Lagi
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- HERO BLOCK --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="relative bg-gradient-to-br from-brand-600 via-brand-800 to-fuchsia-800 p-8 text-white">
                    <div class="absolute inset-0 bg-grid opacity-10"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-medium uppercase tracking-wide text-brand-100">{{ $g['subheadline'] ?? '' }}</p>
                            <button @click="copy('headline', @js($g['headline'] ?? ''))"
                                class="shrink-0 rounded-full bg-white/10 p-1.5 text-white/80 hover:bg-white/20 hover:text-white" title="Copy headline">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            </button>
                        </div>
                        <h2 class="mt-2 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $g['headline'] ?? '' }}</h2>
                        <p class="mt-4 text-brand-50">{{ $g['description'] ?? '' }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Harga</p>
                        <p class="text-xl font-bold text-brand-700">{{ $g['price'] ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="copy('cta', @js($g['call_to_action'] ?? ''))"
                            class="rounded-full bg-white p-1.5 text-slate-500 hover:text-slate-900" title="Copy CTA">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        </button>
                        <span class="inline-flex rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 px-5 py-2.5 font-medium text-white shadow-soft">{{ $g['call_to_action'] ?? '' }}</span>
                    </div>
                </div>
            </section>

            {{-- BENEFITS --}}
            @if(!empty($g['benefits']))
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-semibold">Manfaat</h3>
                        <button @click="copy('benefits', @js(implode('\n- ', $g['benefits'])))"
                            class="text-xs text-slate-500 hover:text-slate-900">
                            <span x-text="copied==='benefits' ? '✓ tersalin' : 'copy semua'"></span>
                        </button>
                    </div>
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach($g['benefits'] as $b)
                            <li class="flex items-start gap-3 rounded-xl bg-emerald-50/60 p-3">
                                <span class="mt-0.5 grid h-6 w-6 shrink-0 place-content-center rounded-full bg-emerald-500 text-white">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm text-slate-700">{{ $b }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- FEATURES --}}
            @if(!empty($g['features']))
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
                    <h3 class="mb-4 font-semibold">Fitur</h3>
                    <ul class="divide-y divide-slate-100">
                        @foreach($g['features'] as $f)
                            <li class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                                <span class="mt-0.5 grid h-6 w-6 shrink-0 place-content-center rounded-md bg-brand-50 text-brand-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                                </span>
                                <span class="text-sm text-slate-700">{{ $f }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- SOCIAL PROOF --}}
            @if(!empty($g['social_proof']))
                <section class="rounded-2xl border-l-4 border-brand-500 bg-brand-50/40 p-6">
                    <svg class="h-5 w-5 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M7 11l-3 9h5l3-9H7zm9 0l-3 9h5l3-9h-5z" opacity=".8"/></svg>
                    <p class="mt-3 text-lg italic leading-relaxed text-slate-700">{{ $g['social_proof'] }}</p>
                </section>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <aside class="space-y-4 lg:sticky lg:top-20 lg:self-start">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <h3 class="text-sm font-semibold">Input Anda</h3>
                <dl class="mt-3 space-y-2.5 text-sm">
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <div class="flex-1"><div class="text-xs text-slate-500">Audiens</div><div>{{ $page->input_data['target_audience'] ?? '—' }}</div></div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-8-4 18-4-8-4-1z"/></svg>
                        <div class="flex-1"><div class="text-xs text-slate-500">Tone</div><div>{{ $page->input_data['tone'] ?? '—' }}</div></div>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7l3-7z"/></svg>
                        <div class="flex-1"><div class="text-xs text-slate-500">USP</div><div>{{ $page->input_data['usp'] ?? '—' }}</div></div>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold">Ringkasan Konteks</h3>
                    <button @click="copy('summary', @js($page->context_summary ?? ''))"
                        class="text-xs text-slate-500 hover:text-slate-900">
                        <span x-text="copied==='summary' ? '✓ tersalin' : 'copy'"></span>
                    </button>
                </div>
                <p class="mt-2 break-words rounded-lg bg-slate-50 p-3 text-xs text-slate-600">{{ $page->context_summary }}</p>
                <p class="mt-2 text-xs text-slate-400">Ringkasan ini akan masuk sebagai konteks untuk generasi berikutnya.</p>
            </div>

            <details class="rounded-2xl border border-slate-200 bg-white shadow-soft">
                <summary class="flex cursor-pointer items-center justify-between p-5 text-sm font-semibold">
                    Raw JSON
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <div class="border-t border-slate-100 p-5 pt-3">
                    <pre class="max-h-80 overflow-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">{{ json_encode($g, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </details>

            <form method="POST" action="{{ route('sales-pages.destroy', $page) }}"
                  onsubmit="return confirm('Hapus halaman ini? Tindakan tidak dapat dibatalkan.')">
                @csrf @method('DELETE')
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-rose-200 bg-white px-3 py-2 text-sm text-rose-600 hover:bg-rose-50">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    Hapus Halaman
                </button>
            </form>
        </aside>
    </div>

    {{-- Toast copy feedback --}}
    <div class="pointer-events-none fixed bottom-6 left-1/2 z-50 -translate-x-1/2">
        <div x-show="copied" x-transition x-cloak
             class="pointer-events-auto rounded-full bg-slate-900 px-4 py-2 text-sm text-white shadow-lg">
            <span x-text="copied === 'all' ? 'JSON lengkap tersalin ke clipboard' : 'Tersalin ke clipboard'"></span>
        </div>
    </div>

</div>
@endsection
