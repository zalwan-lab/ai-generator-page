@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="mx-auto grid max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft lg:grid-cols-2">
    <div class="p-8 sm:p-10">
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight">Selamat datang kembali</h1>
            <p class="mt-1 text-sm text-slate-500">Masuk untuk melanjutkan menghasilkan halaman penjualan.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPw:false }">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-content-center text-slate-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4zM4 8l8 5 8-5"/></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="w-full rounded-lg border-slate-300 pl-10 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Password</label>
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-content-center text-slate-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/></svg>
                    </span>
                    <input :type="showPw ? 'text' : 'password'" name="password" required autocomplete="current-password"
                        class="w-full rounded-lg border-slate-300 pl-10 pr-10 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    <button type="button" @click="showPw=!showPw" class="absolute inset-y-0 right-0 grid w-10 place-content-center text-slate-400 hover:text-slate-600">
                        <svg x-show="!showPw" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="showPw" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"> Ingat saya
                </label>
            </div>

            <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-4 py-2.5 font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
                Masuk
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-brand-600 hover:underline">Daftar gratis</a>
        </p>
    </div>

    <div class="relative hidden overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-fuchsia-800 p-10 text-white lg:block">
        <div class="absolute -top-20 -right-20 h-60 w-60 rounded-full bg-white/10 blur-3xl animate-float"></div>
        <div class="absolute bottom-0 -left-10 h-60 w-60 rounded-full bg-fuchsia-400/20 blur-3xl animate-float" style="animation-delay:-4s"></div>

        <div class="relative flex h-full flex-col justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-brand-100 hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                kembali ke beranda
            </a>
            <div>
                <blockquote class="text-lg leading-relaxed">
                    "Copy kami jadi konsisten tanpa harus nulis brand book 12 halaman. Context engine-nya yang bikin beda."
                </blockquote>
                <div class="mt-4 flex items-center gap-3">
                    <div class="grid h-10 w-10 place-content-center rounded-full bg-white/20 font-semibold">A</div>
                    <div>
                        <div class="font-semibold">Andi Wijaya</div>
                        <div class="text-sm text-brand-200">Marketing Lead, Studio Kreatif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
