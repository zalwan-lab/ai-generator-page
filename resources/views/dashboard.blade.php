@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Halo, {{ auth()->user()->name }}</h1>
    </div>
    <a href="{{ route('sales-pages.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-4 py-2.5 text-sm font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Halaman Baru
    </a>
</div>

{{-- STAT CARDS --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Total Halaman</p>
            <span class="grid h-8 w-8 place-content-center rounded-lg bg-brand-50 text-brand-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            </span>
        </div>
        <p class="mt-2 text-3xl font-bold">{{ $totalPages }}</p>
        <p class="text-xs text-slate-500">halaman tersimpan</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Konteks Aktif</p>
            <span class="grid h-8 w-8 place-content-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
            </span>
        </div>
        <p class="mt-2 text-3xl font-bold">{{ $context['used'] }} <span class="text-base font-medium text-slate-400">/ 5</span></p>
        @php $progress = ($context['used'] / 5) * 100; @endphp
        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-fuchsia-500 transition-all" @style(["width: {$progress}%"])></div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Tone Dominan</p>
            <span class="grid h-8 w-8 place-content-center rounded-lg bg-emerald-50 text-emerald-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-8-4 18-4-8-4-1z"/></svg>
            </span>
        </div>
        <p class="mt-2 line-clamp-1 text-xl font-semibold">{{ $context['patterns']['dominant_tone'] ?? '—' }}</p>
        <p class="text-xs text-slate-500">paling sering dipakai</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Audiens Dominan</p>
            <span class="grid h-8 w-8 place-content-center rounded-lg bg-amber-50 text-amber-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </span>
        </div>
        <p class="mt-2 line-clamp-1 text-xl font-semibold">{{ $context['patterns']['dominant_audience'] ?? '—' }}</p>
        <p class="text-xs text-slate-500">pola yang terdeteksi</p>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-5">
    {{-- RECENT --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-soft lg:col-span-3">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="font-semibold">Halaman Terakhir</h2>
            <a href="{{ route('sales-pages.index') }}" class="text-sm text-brand-600 hover:underline">Lihat semua →</a>
        </div>
        @if($recent->isEmpty())
            <div class="px-5 py-10 text-center">
                <div class="mx-auto grid h-12 w-12 place-content-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                </div>
                <p class="mt-3 font-medium text-slate-700">Belum ada halaman</p>
                <p class="mt-1 text-sm text-slate-500">Buat halaman pertama Anda, konteks akan mulai terbentuk.</p>
                <a href="{{ route('sales-pages.create') }}" class="mt-4 inline-flex rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">+ Buat Sekarang</a>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($recent as $p)
                    <li>
                        <a href="{{ route('sales-pages.show', $p) }}" class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50">
                            <span class="mt-0.5 grid h-9 w-9 shrink-0 place-content-center rounded-lg bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-semibold text-white">
                                {{ strtoupper(substr($p->product_name,0,1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium">{{ $p->product_name }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $p->generated_content['headline'] ?? '—' }}</p>
                            </div>
                            <div class="ml-auto whitespace-nowrap text-right">
                                <p class="text-xs text-slate-500">{{ $p->created_at->diffForHumans() }}</p>
                                <p class="text-xs text-slate-400">{{ $p->input_data['target_audience'] ?? '—' }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- CONTEXT VIEWER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-soft lg:col-span-2" x-data="{ tab:'summary' }">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold">Konteks Injeksi</h2>
                <p class="text-xs text-slate-500">Yang dilihat LLM sebelum generasi berikutnya</p>
            </div>
            <div class="flex items-center rounded-lg bg-slate-100 p-0.5 text-xs">
                <button @click="tab='summary'" :class="tab==='summary' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500'" class="rounded-md px-2.5 py-1">Ringkasan</button>
                <button @click="tab='avoid'"   :class="tab==='avoid'   ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500'" class="rounded-md px-2.5 py-1">Avoid</button>
            </div>
        </div>
        <div class="p-5">
            <div x-show="tab==='summary'">
                <pre class="max-h-80 overflow-auto whitespace-pre-wrap rounded-lg bg-slate-900 p-4 text-xs leading-relaxed text-slate-100">{{ $context['summary'] }}</pre>
            </div>
            <div x-show="tab==='avoid'" x-cloak>
                @php $a = $context['avoid'] ?? ['headlines'=>[],'ctas'=>[]]; @endphp
                @if(empty($a['headlines']) && empty($a['ctas']))
                    <p class="py-8 text-center text-sm text-slate-500">Belum ada item yang harus dihindari.</p>
                @else
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Headline sebelumnya</p>
                            <ul class="mt-2 space-y-1.5">
                                @foreach($a['headlines'] as $h)
                                    <li class="rounded-md bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $h }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">CTA sebelumnya</p>
                            <ul class="mt-2 space-y-1.5">
                                @foreach($a['ctas'] as $c)
                                    <li class="rounded-md bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $c }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
