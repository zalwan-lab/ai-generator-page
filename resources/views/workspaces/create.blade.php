@extends('layouts.app')
@section('title', 'Workspace Baru')

@section('content')
<div class="mx-auto max-w-xl">
    <nav class="mb-2 text-xs text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">/</span>
        <span class="text-slate-700">Workspace Baru</span>
    </nav>
    <h1 class="text-2xl font-bold tracking-tight">Buat Workspace Baru</h1>
    <p class="mt-1 text-sm text-slate-500">Workspace punya konteks & riwayat halaman sendiri. Ideal untuk memisahkan brand / proyek yang berbeda.</p>

    @if($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('workspaces.store') }}"
          x-data="{ color:'{{ old('color','brand') }}' }"
          class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <div class="mt-1 flex gap-2">
                <input type="text" name="emoji" value="{{ old('emoji') }}" maxlength="4" placeholder="🚀"
                    class="w-14 rounded-lg border-slate-300 text-center shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                    placeholder="Contoh: YogaStudio, MarketBoost, Client X"
                    class="flex-1 rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Warna</label>
            <input type="hidden" name="color" x-model="color">
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach(\App\Models\Workspace::COLORS as $c)
                    @php
                        $bg = ['brand'=>'bg-brand-500','emerald'=>'bg-emerald-500','rose'=>'bg-rose-500','amber'=>'bg-amber-500','sky'=>'bg-sky-500','fuchsia'=>'bg-fuchsia-500'][$c];
                        $ring = ['brand'=>'ring-brand-500','emerald'=>'ring-emerald-500','rose'=>'ring-rose-500','amber'=>'ring-amber-500','sky'=>'ring-sky-500','fuchsia'=>'ring-fuchsia-500'][$c];
                    @endphp
                    <button type="button"
                        onclick="this.closest('form').__x?.$data && (this.closest('form').__x.$data.color='{{ $c }}');
                                 document.querySelector('input[name=color]').value='{{ $c }}';
                                 this.parentElement.querySelectorAll('button').forEach(b=>b.classList.remove('ring-2','ring-offset-2'));
                                 this.classList.add('ring-2','ring-offset-2','{{ $ring }}');"
                        :class="color === @js($c) ? 'ring-2 ring-offset-2 {{ $ring }}' : 'ring-1 ring-slate-200'"
                        class="h-9 w-9 rounded-lg {{ $bg }}" title="{{ $c }}"></button>
                @endforeach
            </div>
            <p class="mt-1 text-xs text-slate-500">Pilihan warna muncul di nav & kartu halaman.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Deskripsi <span class="text-xs text-slate-400">(opsional)</span></label>
            <textarea name="description" rows="2" maxlength="500" placeholder="Untuk brand / proyek apa workspace ini?"
                class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-1">
            <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 px-4 py-2 text-sm font-medium text-white hover:from-brand-700 hover:to-brand-900">
                Buat & Aktifkan
            </button>
        </div>
    </form>
</div>
@endsection
