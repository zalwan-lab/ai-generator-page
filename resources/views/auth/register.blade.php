@extends('layouts.app')
@section('title', 'Daftar')

@section('content')
<div class="mx-auto grid max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft lg:grid-cols-2">
    <div class="p-8 sm:p-10">
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight">Buat akun</h1>
            <p class="mt-1 text-sm text-slate-500">Mulai menghasilkan halaman penjualan dengan konteks yang konsisten.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ pw:'', showPw:false, strength(){ const p=this.pw; let s=0; if(p.length>=8) s++; if(/[A-Z]/.test(p)) s++; if(/[0-9]/.test(p)) s++; if(/[^a-zA-Z0-9]/.test(p)) s++; return s; } }">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Password</label>
                <div class="relative mt-1">
                    <input :type="showPw?'text':'password'" name="password" x-model="pw" required autocomplete="new-password"
                        class="w-full rounded-lg border-slate-300 pr-10 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    <button type="button" @click="showPw=!showPw" class="absolute inset-y-0 right-0 grid w-10 place-content-center text-slate-400 hover:text-slate-600">
                        <svg x-show="!showPw" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="showPw" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                    </button>
                </div>
                <div class="mt-2 flex gap-1" aria-hidden="true">
                    <template x-for="i in 4">
                        <div class="h-1 flex-1 rounded-full"
                             :class="strength() >= i ? (strength()<=1?'bg-rose-400':strength()<=2?'bg-amber-400':strength()<=3?'bg-emerald-400':'bg-emerald-500') : 'bg-slate-200'"></div>
                    </template>
                </div>
                <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter. Campur huruf besar, angka, dan simbol untuk kekuatan penuh.</p>
                @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                    class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>

            <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-4 py-2.5 font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
                Buat Akun
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-brand-600 hover:underline">Masuk</a>
        </p>
    </div>

    <div class="relative hidden overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-fuchsia-800 p-10 text-white lg:block">
        <div class="absolute -top-20 -right-20 h-60 w-60 rounded-full bg-white/10 blur-3xl animate-float"></div>

        <div class="relative flex h-full flex-col justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-brand-100 hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                kembali ke beranda
            </a>
            <div>
                <h2 class="text-2xl font-bold">Yang kamu dapat hari pertama</h2>
                <ul class="mt-5 space-y-3 text-brand-50">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-content-center rounded-full bg-white/15"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg></span>
                        Konteks otomatis dari 5 generasi terakhir
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-content-center rounded-full bg-white/15"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg></span>
                        JSON schema ketat — 0 parse error di UI
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-content-center rounded-full bg-white/15"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg></span>
                        Preview penuh sebagai landing page siap publish
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
