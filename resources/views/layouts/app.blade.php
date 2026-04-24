<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI Sales Page Generator')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,line-clamp"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',
                            400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',
                            800:'#5b21b6',900:'#4c1d95',950:'#2e1065',
                        },
                    },
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui','sans-serif'] },
                    boxShadow: { 'soft': '0 1px 2px rgba(16,24,40,.04), 0 1px 3px rgba(16,24,40,.06)' },
                    animation: {
                        'fade-up': 'fade-up .5s ease-out both',
                        'float':   'float 8s ease-in-out infinite',
                    },
                    keyframes: {
                        'fade-up': { '0%':{opacity:'0',transform:'translateY(8px)'},'100%':{opacity:'1',transform:'translateY(0)'} },
                        'float':   { '0%,100%':{transform:'translateY(0) translateX(0)'}, '50%':{transform:'translateY(-12px) translateX(6px)'} },
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .bg-grid { background-image: radial-gradient(rgba(124,58,237,.08) 1px, transparent 1px); background-size: 18px 18px; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
</head>
<body class="min-h-full bg-slate-50 text-slate-900 antialiased"
      x-data="{
        toast: @js(session('status')),
        error: @js($errors->any() ? $errors->first() : null),
        showToast(msg){ this.toast = msg; setTimeout(()=>this.toast=null, 4000) }
      }">

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 py-3">
            <a href="{{ route('home') }}" class="group flex items-center gap-2 font-semibold">
                <span class="grid h-9 w-9 place-content-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-soft ring-1 ring-black/5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M12 2l2.5 6.5L21 11l-6.5 2.5L12 20l-2.5-6.5L3 11l6.5-2.5L12 2z"/></svg>
                </span>
                <span class="tracking-tight">Sales Page AI</span>
            </a>

            <nav class="hidden items-center gap-1 sm:flex">
                @auth
                    @php $r = request()->route()?->getName(); @endphp
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-sm {{ $r==='dashboard' ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100' }}">Dashboard</a>
                    <a href="{{ route('sales-pages.index') }}" class="rounded-lg px-3 py-2 text-sm {{ str_starts_with((string)$r,'sales-pages.') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100' }}">Riwayat</a>
                    <a href="{{ route('sales-pages.create') }}" class="ml-1 inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-br from-brand-600 to-brand-700 px-3.5 py-2 text-sm font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        Halaman Baru
                    </a>

                    <div class="relative ml-2" x-data="{ open:false }" @click.outside="open=false">
                        <button @click="open=!open" class="flex items-center gap-2 rounded-full p-1 pr-3 hover:bg-slate-100">
                            <span class="grid h-8 w-8 place-content-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                            <span class="text-sm text-slate-700">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.150ms x-cloak class="absolute right-0 mt-1 w-48 rounded-lg border border-slate-200 bg-white p-1 shadow-lg">
                            <div class="border-b border-slate-100 px-3 py-2 text-xs text-slate-500">{{ auth()->user()->email }}</div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 17l5-5-5-5M20 12H9M13 21H6a2 2 0 01-2-2V5a2 2 0 012-2h7"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-gradient-to-br from-brand-600 to-brand-700 px-3.5 py-2 text-sm font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-800">Daftar Gratis</a>
                @endauth
            </nav>

            <button class="sm:hidden rounded-lg p-2 hover:bg-slate-100" x-data @click="$dispatch('toggle-mobile')">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
        </div>

        <div x-data="{open:false}" @toggle-mobile.window="open=!open" x-show="open" x-transition x-cloak class="sm:hidden border-t border-slate-200 bg-white px-4 py-3">
            @auth
                <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Dashboard</a>
                <a href="{{ route('sales-pages.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Riwayat</a>
                <a href="{{ route('sales-pages.create') }}" class="mt-1 block rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white">+ Halaman Baru</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1"> @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="block rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white">Daftar Gratis</a>
            @endauth
        </div>
    </header>

    {{-- Toasts --}}
    <div class="pointer-events-none fixed right-4 top-20 z-50 flex w-full max-w-sm flex-col gap-2">
        <template x-if="toast">
            <div x-show="toast" x-transition.duration.300ms
                 class="pointer-events-auto flex items-start gap-3 rounded-lg border border-emerald-200 bg-white p-4 shadow-lg">
                <span class="mt-0.5 grid h-7 w-7 shrink-0 place-content-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                </span>
                <div class="flex-1 text-sm text-slate-700" x-text="toast"></div>
                <button @click="toast=null" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
        <template x-if="error">
            <div x-show="error" x-transition.duration.300ms
                 class="pointer-events-auto flex items-start gap-3 rounded-lg border border-rose-200 bg-white p-4 shadow-lg">
                <span class="mt-0.5 grid h-7 w-7 shrink-0 place-content-center rounded-full bg-rose-100 text-rose-600">!</span>
                <div class="flex-1 text-sm text-slate-700" x-text="error"></div>
                <button @click="error=null" class="text-slate-400 hover:text-slate-600">×</button>
            </div>
        </template>
    </div>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-slate-200 bg-white/50">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-6 text-xs text-slate-500 sm:flex-row">
            <div>© {{ date('Y') }} Sales Page AI — MCP-lite context engine.</div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> LLM online</span>
                <span>v1.0</span>
            </div>
        </div>
    </footer>

    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
