<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->product_name }} — {{ $page->generated_content['headline'] ?? 'Preview' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-grid { background-image: radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px); background-size: 18px 18px; }
        @keyframes blob { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(40px,-30px) scale(1.1)} 66%{transform:translate(-20px,30px) scale(.95)} }
        .animate-blob { animation: blob 12s ease-in-out infinite; }
        @keyframes fadeUp { 0%{opacity:0;transform:translateY(12px)} 100%{opacity:1;transform:translateY(0)} }
        .animate-fade-up { animation: fadeUp .7s ease-out both; }
    </style>
</head>
<body class="bg-white text-slate-900">
@php $g = $page->generated_content; @endphp

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-violet-700 via-purple-800 to-fuchsia-800 text-white">
    <div class="absolute -top-20 -right-20 h-80 w-80 rounded-full bg-fuchsia-400/30 blur-3xl animate-blob"></div>
    <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-violet-400/30 blur-3xl animate-blob" style="animation-delay:-6s"></div>
    <div class="absolute inset-0 bg-grid"></div>

    <div class="relative mx-auto max-w-5xl px-6 py-24 text-center sm:py-32">
        <div class="animate-fade-up inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium text-white backdrop-blur">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
            {{ $g['subheadline'] ?? '' }}
        </div>
        <h1 class="animate-fade-up mt-6 text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl" style="animation-delay:80ms">
            {{ $g['headline'] ?? '' }}
        </h1>
        <p class="animate-fade-up mx-auto mt-6 max-w-2xl text-lg text-white/80 sm:text-xl" style="animation-delay:160ms">
            {{ $g['description'] ?? '' }}
        </p>
        <div class="animate-fade-up mt-10 flex flex-wrap justify-center gap-3" style="animation-delay:240ms">
            <a href="#pricing" class="group inline-flex items-center gap-2 rounded-full bg-white px-8 py-4 text-base font-semibold text-violet-700 shadow-2xl shadow-fuchsia-900/30 transition hover:scale-105 hover:bg-violet-50">
                {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <a href="#features" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-8 py-4 text-base font-semibold text-white hover:bg-white/10">Pelajari</a>
        </div>

        <div class="animate-fade-up mt-12 flex flex-wrap items-center justify-center gap-6 text-sm text-white/70" style="animation-delay:320ms">
            <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>Tanpa kartu kredit</span>
            <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>Dukungan 24/7</span>
            <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>Garansi 30 hari</span>
        </div>
    </div>
</section>

{{-- BENEFITS --}}
@if(!empty($g['benefits']))
<section id="features" class="bg-white px-6 py-24">
    <div class="mx-auto max-w-5xl">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Kenapa kami</p>
            <h2 class="mt-2 text-4xl font-bold tracking-tight">Solusi yang benar-benar untuk Anda</h2>
        </div>
        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($g['benefits'] as $i => $b)
                @php $colors = [['violet','indigo'],['fuchsia','pink'],['emerald','teal'],['amber','orange'],['sky','cyan'],['rose','pink']]; $c = $colors[$i % count($colors)]; @endphp
                <div class="group relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="grid h-11 w-11 place-content-center rounded-xl bg-gradient-to-br from-{{ $c[0] }}-500 to-{{ $c[1] }}-600 text-white shadow-md">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="mt-4 font-medium leading-relaxed text-slate-700">{{ $b }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURES --}}
@if(!empty($g['features']))
<section class="bg-slate-50 px-6 py-24">
    <div class="mx-auto max-w-5xl">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-fuchsia-600">Fitur lengkap</p>
            <h2 class="mt-2 text-4xl font-bold tracking-tight">Semua yang Anda butuhkan</h2>
        </div>
        <div class="mx-auto mt-14 grid max-w-3xl gap-3">
            @foreach($g['features'] as $f)
                <div class="flex items-start gap-4 rounded-xl bg-white p-5 shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-content-center rounded-lg bg-violet-100 text-violet-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </span>
                    <p class="text-slate-700">{{ $f }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- SOCIAL PROOF --}}
@if(!empty($g['social_proof']))
<section class="bg-white px-6 py-24">
    <div class="mx-auto max-w-3xl">
        <div class="relative rounded-3xl bg-gradient-to-br from-violet-50 via-white to-fuchsia-50 p-10 text-center">
            <svg class="mx-auto h-10 w-10 text-violet-300" viewBox="0 0 24 24" fill="currentColor"><path d="M7 11l-3 9h5l3-9H7zm9 0l-3 9h5l3-9h-5z"/></svg>
            <blockquote class="mt-6 text-2xl font-medium leading-relaxed text-slate-800">
                "{{ $g['social_proof'] }}"
            </blockquote>
            <div class="mt-6 flex items-center justify-center gap-1 text-amber-400">
                @for($i=0;$i<5;$i++)<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7l3-7z"/></svg>@endfor
            </div>
        </div>
    </div>
</section>
@endif

{{-- PRICING / CTA --}}
<section id="pricing" class="bg-gradient-to-br from-violet-50 via-white to-fuchsia-50 px-6 py-24">
    <div class="mx-auto max-w-xl">
        <div class="relative overflow-hidden rounded-3xl border border-violet-200 bg-white p-10 shadow-2xl shadow-violet-200">
            <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-fuchsia-200 blur-3xl"></div>
            <div class="relative text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Penawaran Spesial</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight">{{ $page->product_name }}</h2>
                <div class="mt-6">
                    <div class="text-5xl font-extrabold text-violet-700">{{ $g['price'] ?? '' }}</div>
                </div>
                @if(!empty($g['benefits']))
                    <ul class="mx-auto mt-8 max-w-sm space-y-3 text-left">
                        @foreach(array_slice($g['benefits'],0,5) as $b)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid h-5 w-5 shrink-0 place-content-center rounded-full bg-emerald-500 text-white">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm text-slate-700">{{ $b }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <a href="#" class="mt-10 inline-flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-br from-violet-600 to-fuchsia-600 px-8 py-4 text-lg font-semibold text-white shadow-xl shadow-violet-300 transition hover:scale-105">
                    {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <p class="mt-4 text-xs text-slate-500">Garansi uang kembali 30 hari · dukungan 24/7</p>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="border-t border-slate-200 bg-slate-50 px-6 py-8 text-center text-xs text-slate-500">
    © {{ date('Y') }} {{ $page->product_name }} · Generated by <span class="font-medium text-violet-600">Sales Page AI</span> · MCP-lite context engine
</footer>
</body>
</html>
