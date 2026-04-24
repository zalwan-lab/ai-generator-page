@php
    $g = $page->generated_content;
    $theme = $g['theme'] ?? ['palette' => 'violet', 'mood' => 'bold'];
    $p = \App\Support\Theme::palette($theme['palette'] ?? null);
    $mood = \App\Support\Theme::mood($theme['mood'] ?? null);
    $safelist = \App\Support\Theme::safelist();

    $fontFamilies = match($mood) {
        'elegant'  => 'inter:400,500,700|playfair-display:400,600,700,800',
        'playful'  => 'quicksand:400,500,600,700|inter:400,500',
        default    => 'inter:400,500,600,700,800',
    };
    $bodyFont = match($mood) {
        'playful' => "'Quicksand', ui-sans-serif, system-ui, sans-serif",
        default   => "'Inter', ui-sans-serif, system-ui, sans-serif",
    };
    $headingFont = match($mood) {
        'elegant' => "'Playfair Display', ui-serif, Georgia, serif",
        'playful' => "'Quicksand', ui-sans-serif, system-ui, sans-serif",
        default   => "'Inter', ui-sans-serif, system-ui, sans-serif",
    };
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth" @style(["--body-font: {$bodyFont}", "--heading-font: {$headingFont}"])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->product_name }} — {{ $page->generated_content['headline'] ?? 'Preview' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family={{ $fontFamilies }}&display=swap" rel="stylesheet" />
    <style>
        body { font-family: var(--body-font); }
        h1, h2, h3, .heading { font-family: var(--heading-font); }
        .bg-grid { background-image: radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px); background-size: 18px 18px; }
        .bg-grid-dark { background-image: radial-gradient(rgba(0,0,0,.06) 1px, transparent 1px); background-size: 18px 18px; }
        @keyframes blob { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(40px,-30px) scale(1.1)} 66%{transform:translate(-20px,30px) scale(.95)} }
        .animate-blob { animation: blob 12s ease-in-out infinite; }
        @keyframes fadeUp { 0%{opacity:0;transform:translateY(12px)} 100%{opacity:1;transform:translateY(0)} }
        .animate-fade-up { animation: fadeUp .7s ease-out both; }
    </style>
</head>
<body class="bg-white text-slate-900">

{{-- Tailwind JIT safelist --}}
<div class="hidden {{ $safelist }}" aria-hidden="true"></div>

{{-- ======================================================================
     HERO — varies per mood
====================================================================== --}}
@if($mood === 'bold')
    <section class="relative overflow-hidden bg-gradient-to-br from-{{ $p }}-600 via-{{ $p }}-800 to-{{ $p }}-900 text-white">
        <div class="absolute -top-20 -right-20 h-80 w-80 rounded-full bg-{{ $p }}-400/30 blur-3xl animate-blob"></div>
        <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-{{ $p }}-300/30 blur-3xl animate-blob" style="animation-delay:-6s"></div>
        <div class="absolute inset-0 bg-grid"></div>
        <div class="relative mx-auto max-w-5xl px-6 py-24 text-center sm:py-32">
            <div class="animate-fade-up inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                {{ $g['subheadline'] ?? '' }}
            </div>
            <h1 class="animate-fade-up mt-6 text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl" style="animation-delay:80ms">{{ $g['headline'] ?? '' }}</h1>
            <p class="animate-fade-up mx-auto mt-6 max-w-2xl text-lg text-white/80 sm:text-xl" style="animation-delay:160ms">{{ $g['description'] ?? '' }}</p>
            <div class="animate-fade-up mt-10 flex flex-wrap justify-center gap-3" style="animation-delay:240ms">
                <a href="#pricing" class="group inline-flex items-center gap-2 rounded-full bg-white px-8 py-4 text-base font-semibold text-{{ $p }}-700 shadow-2xl shadow-{{ $p }}-900/30 transition hover:scale-105">
                    {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                    <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a href="#features" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-8 py-4 text-base font-semibold hover:bg-white/10">Pelajari</a>
            </div>
        </div>
    </section>

@elseif($mood === 'minimal')
    <section class="relative bg-white border-b border-slate-200">
        <div class="mx-auto max-w-4xl px-6 py-20 sm:py-28">
            <div class="mx-auto max-w-2xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-medium {{ 'text-'.$p.'-700' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ 'bg-'.$p.'-600' }}"></span>
                    {{ $g['subheadline'] ?? '' }}
                </div>
                <h1 class="animate-fade-up text-4xl font-bold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">{{ $g['headline'] ?? '' }}</h1>
                <p class="animate-fade-up mt-6 max-w-xl text-lg text-slate-600" style="animation-delay:80ms">{{ $g['description'] ?? '' }}</p>
                <div class="animate-fade-up mt-8 flex flex-wrap gap-3" style="animation-delay:160ms">
                    <a href="#pricing" class="inline-flex items-center gap-2 rounded-md {{ 'bg-'.$p.'-600' }} px-6 py-3 text-base font-medium text-white hover:{{ 'bg-'.$p.'-700' }}">
                        {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </a>
                    <a href="#features" class="inline-flex items-center gap-2 rounded-md border border-slate-300 px-6 py-3 text-base font-medium text-slate-700 hover:bg-slate-50">Pelajari</a>
                </div>
            </div>
            <div class="mt-16 h-px w-full bg-gradient-to-r from-transparent {{ 'via-'.$p.'-400' }} to-transparent"></div>
        </div>
    </section>

@elseif($mood === 'elegant')
    <section class="relative bg-gradient-to-b {{ 'from-'.$p.'-50' }} to-white">
        <div class="absolute inset-0 bg-grid-dark opacity-50"></div>
        <div class="relative mx-auto max-w-4xl px-6 py-24 text-center sm:py-32">
            <p class="heading mb-6 text-xs font-medium uppercase tracking-[0.3em] {{ 'text-'.$p.'-700' }}">{{ $g['subheadline'] ?? '' }}</p>
            <h1 class="heading animate-fade-up mx-auto max-w-3xl text-4xl font-semibold leading-[1.15] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">{{ $g['headline'] ?? '' }}</h1>
            <div class="mx-auto my-8 h-px w-16 {{ 'bg-'.$p.'-700' }}"></div>
            <p class="animate-fade-up mx-auto max-w-xl text-lg leading-relaxed text-slate-600" style="animation-delay:80ms">{{ $g['description'] ?? '' }}</p>
            <div class="animate-fade-up mt-12 flex justify-center gap-4" style="animation-delay:160ms">
                <a href="#pricing" class="inline-flex items-center gap-3 {{ 'bg-'.$p.'-800' }} px-10 py-4 text-sm font-medium uppercase tracking-[0.2em] text-white transition hover:{{ 'bg-'.$p.'-900' }}">
                    {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                </a>
            </div>
        </div>
    </section>

@elseif($mood === 'playful')
    <section class="relative overflow-hidden bg-gradient-to-br {{ 'from-'.$p.'-400' }} via-pink-400 to-amber-300 text-white">
        <div class="absolute top-10 left-10 h-32 w-32 rounded-full bg-white/20 blur-2xl animate-blob"></div>
        <div class="absolute bottom-10 right-10 h-40 w-40 rounded-[40%_60%_70%_30%] bg-white/30 blur-2xl animate-blob" style="animation-delay:-3s"></div>
        <div class="absolute top-1/2 left-1/3 h-24 w-24 rounded-[60%_40%_30%_70%] bg-yellow-300/40 blur-xl animate-blob" style="animation-delay:-7s"></div>
        <div class="relative mx-auto max-w-5xl px-6 py-20 text-center sm:py-28">
            <div class="animate-fade-up inline-flex items-center gap-2 rounded-full bg-white/30 px-4 py-1.5 text-sm font-bold backdrop-blur">
                ✨ {{ $g['subheadline'] ?? '' }} ✨
            </div>
            <h1 class="animate-fade-up mt-6 text-5xl font-black leading-[1.05] tracking-tight drop-shadow-md sm:text-6xl lg:text-7xl" style="animation-delay:80ms">{{ $g['headline'] ?? '' }}</h1>
            <p class="animate-fade-up mx-auto mt-6 max-w-2xl text-lg font-medium text-white/95 sm:text-xl" style="animation-delay:160ms">{{ $g['description'] ?? '' }}</p>
            <div class="animate-fade-up mt-10 flex flex-wrap justify-center gap-3" style="animation-delay:240ms">
                <a href="#pricing" class="group inline-flex items-center gap-2 rounded-full bg-white px-8 py-4 text-lg font-bold {{ 'text-'.$p.'-700' }} shadow-2xl transition hover:scale-110 hover:rotate-2">
                    {{ $g['call_to_action'] ?? 'Yuk Mulai!' }}
                    <span class="transition group-hover:translate-x-1">🚀</span>
                </a>
            </div>
        </div>
    </section>
@endif

{{-- ======================================================================
     BENEFITS — per-mood card style
====================================================================== --}}
@if(!empty($g['benefits']))
<section id="features" class="bg-white px-6 py-24">
    <div class="mx-auto max-w-5xl">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider {{ 'text-'.$p.'-600' }}">Kenapa kami</p>
            <h2 class="heading mt-2 text-4xl font-bold tracking-tight {{ $mood === 'elegant' ? 'font-semibold' : '' }}">Solusi yang benar-benar untuk Anda</h2>
        </div>
        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($g['benefits'] as $i => $b)
                @if($mood === 'bold' || $mood === 'minimal')
                    <div class="group relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="grid h-11 w-11 place-content-center rounded-xl {{ 'bg-'.$p.'-600' }} text-white shadow-md">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="mt-4 font-medium leading-relaxed text-slate-700">{{ $b }}</p>
                    </div>
                @elseif($mood === 'elegant')
                    <div class="group relative border-t {{ 'border-'.$p.'-200' }} pt-8">
                        <div class="text-sm font-medium tracking-[0.2em] {{ 'text-'.$p.'-700' }}">0{{ $i+1 }}.</div>
                        <p class="heading mt-3 text-lg leading-relaxed text-slate-800">{{ $b }}</p>
                    </div>
                @elseif($mood === 'playful')
                    @php $rotations = ['-rotate-1','rotate-1','-rotate-2','rotate-2','rotate-0']; @endphp
                    <div class="group relative rounded-3xl {{ 'bg-'.$p.'-50' }} p-6 shadow-lg transition hover:-translate-y-2 hover:rotate-0 {{ $rotations[$i % count($rotations)] }}">
                        <div class="grid h-12 w-12 place-content-center rounded-2xl {{ 'bg-'.$p.'-500' }} text-2xl text-white shadow-lg">
                            {{ ['🎯','💡','🚀','✨','🎉','🌈','⭐','🔥'][$i % 8] }}
                        </div>
                        <p class="mt-4 font-semibold leading-relaxed text-slate-700">{{ $b }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================================================================
     FEATURES
====================================================================== --}}
@if(!empty($g['features']))
<section class="{{ $mood === 'elegant' ? 'bg-white' : 'bg-slate-50' }} px-6 py-24">
    <div class="mx-auto max-w-5xl">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider {{ 'text-'.$p.'-600' }}">Fitur lengkap</p>
            <h2 class="heading mt-2 text-4xl font-bold tracking-tight">Semua yang Anda butuhkan</h2>
        </div>
        <div class="mx-auto mt-14 grid max-w-3xl gap-3">
            @foreach($g['features'] as $f)
                @if($mood === 'elegant')
                    <div class="flex items-start gap-4 border-b border-slate-200 py-4 last:border-b-0">
                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ 'bg-'.$p.'-700' }}"></span>
                        <p class="text-slate-700">{{ $f }}</p>
                    </div>
                @elseif($mood === 'playful')
                    <div class="flex items-start gap-4 rounded-2xl bg-white p-5 shadow-md">
                        <span class="grid h-8 w-8 shrink-0 place-content-center rounded-xl {{ 'bg-'.$p.'-100' }} text-lg">✓</span>
                        <p class="font-medium text-slate-700">{{ $f }}</p>
                    </div>
                @else
                    <div class="flex items-start gap-4 rounded-xl bg-white p-5 shadow-sm">
                        <span class="grid h-8 w-8 shrink-0 place-content-center rounded-lg {{ 'bg-'.$p.'-100' }} {{ 'text-'.$p.'-600' }}">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                        </span>
                        <p class="text-slate-700">{{ $f }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================================================================
     SOCIAL PROOF
====================================================================== --}}
@if(!empty($g['social_proof']))
<section class="bg-white px-6 py-24">
    <div class="mx-auto max-w-3xl">
        @if($mood === 'elegant')
            <div class="text-center">
                <svg class="mx-auto h-10 w-10 {{ 'text-'.$p.'-700' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M7 11l-3 9h5l3-9H7zm9 0l-3 9h5l3-9h-5z"/></svg>
                <blockquote class="heading mt-8 text-2xl font-medium italic leading-relaxed text-slate-800 sm:text-3xl">"{{ $g['social_proof'] }}"</blockquote>
            </div>
        @else
            <div class="relative rounded-3xl bg-gradient-to-br {{ 'from-'.$p.'-50' }} via-white {{ 'to-'.$p.'-50' }} p-10 text-center">
                <svg class="mx-auto h-10 w-10 {{ 'text-'.$p.'-300' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M7 11l-3 9h5l3-9H7zm9 0l-3 9h5l3-9h-5z"/></svg>
                <blockquote class="mt-6 text-2xl font-medium leading-relaxed text-slate-800">"{{ $g['social_proof'] }}"</blockquote>
                <div class="mt-6 flex items-center justify-center gap-1 text-amber-400">
                    @for($i=0;$i<5;$i++)<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7l3-7z"/></svg>@endfor
                </div>
            </div>
        @endif
    </div>
</section>
@endif

{{-- ======================================================================
     PRICING / CTA
====================================================================== --}}
<section id="pricing" class="bg-gradient-to-br {{ 'from-'.$p.'-50' }} via-white {{ 'to-'.$p.'-50' }} px-6 py-24">
    <div class="mx-auto max-w-xl">
        <div class="relative overflow-hidden {{ $mood === 'playful' ? 'rounded-[2.5rem]' : 'rounded-3xl' }} border {{ 'border-'.$p.'-200' }} bg-white p-10 shadow-2xl {{ 'shadow-'.$p.'-200' }}">
            <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full {{ 'bg-'.$p.'-200' }} blur-3xl"></div>
            <div class="relative text-center">
                <p class="text-sm font-semibold uppercase tracking-wider {{ 'text-'.$p.'-600' }}">Penawaran Spesial</p>
                <h2 class="heading mt-2 text-3xl font-bold tracking-tight">{{ $page->product_name }}</h2>
                <div class="mt-6">
                    <div class="text-5xl font-extrabold {{ 'text-'.$p.'-700' }}">{{ $g['price'] ?? '' }}</div>
                </div>
                @if(!empty($g['benefits']))
                    <ul class="mx-auto mt-8 max-w-sm space-y-3 text-left">
                        @foreach(array_slice($g['benefits'],0,5) as $b)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid h-5 w-5 shrink-0 place-content-center rounded-full {{ 'bg-'.$p.'-600' }} text-white">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="text-sm text-slate-700">{{ $b }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <a href="#" class="mt-10 inline-flex w-full items-center justify-center gap-2 {{ $mood === 'elegant' ? 'rounded-none' : ($mood === 'playful' ? 'rounded-full' : 'rounded-xl') }} bg-gradient-to-br {{ 'from-'.$p.'-600' }} {{ 'to-'.$p.'-800' }} px-8 py-4 text-lg font-semibold text-white shadow-xl {{ 'shadow-'.$p.'-300' }} transition hover:scale-105">
                    {{ $g['call_to_action'] ?? 'Pesan Sekarang' }}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <p class="mt-4 text-xs text-slate-500">Garansi uang kembali 30 hari · dukungan 24/7</p>
            </div>
        </div>
    </div>
</section>

<footer class="border-t border-slate-200 bg-slate-50 px-6 py-8 text-center text-xs text-slate-500">
    © {{ date('Y') }} {{ $page->product_name }} ·
    <span class="font-medium">theme: {{ $p }} / {{ $mood }}</span> ·
    Generated by Sales Page AI
</footer>
</body>
</html>
