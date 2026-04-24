@extends('layouts.app')
@section('title', 'Sales Page AI — Halaman penjualan berbasis konteks')

@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white">
    <div class="absolute inset-0 bg-grid"></div>
    <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-brand-400/30 blur-3xl animate-float"></div>
    <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-fuchsia-300/30 blur-3xl animate-float" style="animation-delay:-3s"></div>

    <div class="relative px-6 py-20 text-center sm:px-10 sm:py-28">
        <div class="animate-fade-up mx-auto mb-5 inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white/60 px-3 py-1 text-xs font-medium text-brand-700 backdrop-blur">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
            MCP-lite context engine · Bahasa Indonesia
        </div>
        <h1 class="animate-fade-up text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl" style="animation-delay:50ms">
            Halaman penjualan yang
            <span class="bg-gradient-to-br from-brand-600 to-fuchsia-500 bg-clip-text text-transparent">konsisten</span>,
            bukan satu kali jadi.
        </h1>
        <p class="animate-fade-up mx-auto mt-5 max-w-2xl text-lg text-slate-600" style="animation-delay:120ms">
            Setiap generasi belajar dari riwayat Anda. Tone, audiens, dan positioning tetap selaras
            — konteks disuntikkan otomatis ke prompt AI.
        </p>
        <div class="animate-fade-up mt-8 flex flex-wrap justify-center gap-3" style="animation-delay:200ms">
            @auth
                <a href="{{ route('sales-pages.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-3 font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
                    Buat Halaman
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 hover:bg-slate-50">Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-3 font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
                    Mulai Gratis
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 hover:bg-slate-50">Sudah punya akun?</a>
            @endauth
        </div>

        <div class="animate-fade-up mx-auto mt-14 grid max-w-3xl grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 bg-white/70 backdrop-blur" style="animation-delay:280ms">
            <div class="p-5">
                <div class="text-2xl font-bold text-slate-900">5</div>
                <div class="text-xs text-slate-500">generasi terakhir dipakai sebagai konteks</div>
            </div>
            <div class="p-5">
                <div class="text-2xl font-bold text-slate-900">~600</div>
                <div class="text-xs text-slate-500">token budget konteks per prompt</div>
            </div>
            <div class="p-5">
                <div class="text-2xl font-bold text-slate-900">8</div>
                <div class="text-xs text-slate-500">field JSON terstruktur (strict schema)</div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="mt-16">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-bold tracking-tight">Dibangun untuk copy yang tidak membingungkan pelanggan</h2>
        <p class="mx-auto mt-2 max-w-xl text-slate-600">Tiga jaminan engine: konsistensi, tanpa repetisi, dan prompt yang tetap ramping.</p>
    </div>
    <div class="grid gap-6 md:grid-cols-3">
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="grid h-10 w-10 place-content-center rounded-xl bg-brand-50 text-brand-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path d="M12 7v5l3 3"/></svg>
            </div>
            <h3 class="mt-4 font-semibold">Konteks Otomatis</h3>
            <p class="mt-1.5 text-sm text-slate-600">Lima generasi terakhir dianalisis — tone dominan, audiens dominan, dan USP terkini disuntikkan ke prompt.</p>
        </div>
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="grid h-10 w-10 place-content-center rounded-xl bg-fuchsia-50 text-fuchsia-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h12"/></svg>
            </div>
            <h3 class="mt-4 font-semibold">Anti-Repetisi</h3>
            <p class="mt-1.5 text-sm text-slate-600">Daftar headline & CTA sebelumnya dikirim sebagai "do-not-repeat" list agar setiap halaman terasa segar.</p>
        </div>
        <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="grid h-10 w-10 place-content-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </div>
            <h3 class="mt-4 font-semibold">JSON Schema Ketat</h3>
            <p class="mt-1.5 text-sm text-slate-600">Output dipaksa mengikuti skema 8-field via <code class="rounded bg-slate-100 px-1 text-xs">response_format: json_schema</code>. Tidak akan error di UI.</p>
        </div>
    </div>
</section>

{{-- FLOW DIAGRAM --}}
<section class="mt-16 rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-8 sm:p-12">
    <h2 class="text-center text-2xl font-bold tracking-tight">Bagaimana alurnya</h2>
    <p class="mx-auto mt-2 max-w-xl text-center text-slate-600">Satu request di UI — empat langkah di balik layar.</p>

    <div class="mt-10 grid gap-4 md:grid-cols-4">
        @foreach([
            ['1','Input produk','Nama, deskripsi, audiens, harga, USP, tone.','M12 5v14M5 12h14'],
            ['2','Retrieve konteks','ContextManager ambil 5 halaman terakhir, ekstrak pola.','M21 15a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10z'],
            ['3','Inject ke prompt','Ringkasan + avoid-list disatukan ke chat payload.','M4 4h16v16H4zM4 8h16M8 4v16'],
            ['4','LLM → JSON','Output divalidasi schema lalu disimpan ke MySQL.','M12 2l2.5 6.5L21 11l-6.5 2.5L12 20l-2.5-6.5L3 11l6.5-2.5L12 2z'],
        ] as $s)
            <div class="relative rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="absolute -top-3 left-5 grid h-7 w-7 place-content-center rounded-full bg-brand-600 text-xs font-bold text-white">{{ $s[0] }}</div>
                <div class="mt-2 font-semibold">{{ $s[1] }}</div>
                <p class="mt-1 text-sm text-slate-600">{{ $s[2] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="mt-16 overflow-hidden rounded-3xl bg-gradient-to-br from-brand-700 via-brand-800 to-fuchsia-800 p-10 text-center text-white sm:p-14">
    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Siap mulai menulis halaman Anda?</h2>
    <p class="mx-auto mt-3 max-w-xl text-brand-100">Gratis dipakai dengan endpoint LLM apapun yang kompatibel OpenAI — OpenAI, Groq, OpenRouter, atau Ollama lokal.</p>
    <div class="mt-7 flex justify-center gap-3">
        @auth
            <a href="{{ route('sales-pages.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-medium text-brand-700 shadow-lg hover:bg-brand-50">Buat Halaman Sekarang</a>
        @else
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-medium text-brand-700 shadow-lg hover:bg-brand-50">Daftar Gratis</a>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-6 py-3 font-medium text-white hover:bg-white/10">Masuk</a>
        @endauth
    </div>
</section>
@endsection
