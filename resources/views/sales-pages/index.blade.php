@extends('layouts.app')
@section('title', 'Riwayat Halaman')

@section('content')
@php $wc = $workspace->colorClasses(); @endphp
<div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <div class="inline-flex items-center gap-2 rounded-full border {{ $wc['border'] }} {{ $wc['bg50'] }} px-2.5 py-1 text-xs">
            <span class="grid h-4 w-4 place-content-center rounded-sm {{ $wc['bg'] }} text-[10px] font-semibold text-white">{{ $workspace->initial() }}</span>
            <span class="{{ $wc['text'] }} font-medium">{{ $workspace->name }}</span>
        </div>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Riwayat Halaman</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $pages->total() }} halaman di workspace ini · konteks dibangun dari 5 terakhir.</p>
    </div>
    <a href="{{ route('sales-pages.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 px-4 py-2.5 text-sm font-medium text-white shadow-soft hover:from-brand-700 hover:to-brand-900">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Halaman Baru
    </a>
</div>

@if($pages->isEmpty())
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-16 text-center">
        <div class="mx-auto grid h-14 w-14 place-content-center rounded-full bg-brand-50 text-brand-600">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        </div>
        <h2 class="mt-4 text-lg font-semibold">Belum ada halaman</h2>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Buat halaman pertama Anda — konteks akan mulai terbentuk dan halaman berikutnya akan konsisten otomatis.</p>
        <a href="{{ route('sales-pages.create') }}" class="mt-5 inline-flex rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">+ Buat Halaman Pertama</a>
    </div>
@else
    <div x-data="{ q:'' }">
        <div class="relative mb-5 max-w-md">
            <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-content-center text-slate-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </span>
            <input x-model="q" type="text" placeholder="Cari produk atau headline…"
                class="w-full rounded-xl border-slate-300 pl-10 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($pages as $p)
                @php
                    $g = $p->generated_content;
                    $search = mb_strtolower($p->product_name.' '.($g['headline']??''));
                @endphp
                <div x-show="q === '' || @js($search).includes(q.toLowerCase())"
                     class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="relative h-24 bg-gradient-to-br from-brand-500 via-brand-700 to-fuchsia-700 p-4">
                        <div class="absolute inset-0 bg-grid opacity-20"></div>
                        <div class="relative flex h-full items-end justify-between">
                            <span class="rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium text-white backdrop-blur">{{ $p->created_at->diffForHumans() }}</span>
                            <span class="grid h-8 w-8 place-content-center rounded-lg bg-white/15 text-sm font-semibold text-white backdrop-blur">{{ strtoupper(substr($p->product_name,0,1)) }}</span>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="font-semibold">{{ $p->product_name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $g['headline'] ?? '' }}</p>
                        @if($p->input_data['target_audience'] ?? null)
                            <p class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                {{ $p->input_data['target_audience'] }}
                            </p>
                        @endif
                        <div class="mt-auto flex items-center gap-2 pt-4 text-sm">
                            <a href="{{ route('sales-pages.show', $p) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">Detail</a>
                            <a href="{{ route('sales-pages.preview', $p) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                                Preview
                            </a>
                            <form method="POST" action="{{ route('sales-pages.destroy', $p) }}" onsubmit="return confirm('Hapus halaman ini? Tindakan tidak dapat dibatalkan.')" class="ml-auto">
                                @csrf @method('DELETE')
                                <button class="grid h-8 w-8 place-content-center rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Hapus">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $pages->links() }}</div>
    </div>
@endif
@endsection
