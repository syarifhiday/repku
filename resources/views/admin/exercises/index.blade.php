@extends('layouts.app')
@section('title', 'Admin — Gerakan')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-black uppercase">Kelola Gerakan</h1>
        <p class="text-gray-400 text-sm mt-1">Total {{ $exercises->total() }} gerakan terdaftar</p>
    </div>
    <a href="{{ route('admin.exercises.create') }}" class="btn-primary text-sm">+ Tambah Gerakan</a>
</div>

@php
$equipLabels = [
    'dumbell' => 'Dumbbell', 'barbell' => 'Barbell', 'cable' => 'Cable',
    'mesin_gym' => 'Mesin', 'resistance_band' => 'Band', 'pull_up_bar' => 'Pull-Up Bar',
    'kettlebell' => 'Kettlebell', 'bodyweight' => 'Bodyweight', 'lainnya' => 'Lainnya',
];
$diffColor = ['pemula' => '#22c55e', 'menengah' => '#FFD500', 'lanjutan' => '#ef4444'];
@endphp

<div class="card-flat overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr style="background:#1a1a1a; border-bottom:2px solid #2A2A2A;">
                <th class="text-left text-xs uppercase text-gray-400 font-bold px-5 py-3">Gerakan</th>
                <th class="text-left text-xs uppercase text-gray-400 font-bold py-3">Muscle Group</th>
                <th class="text-left text-xs uppercase text-gray-400 font-bold py-3">Alat</th>
                <th class="text-left text-xs uppercase text-gray-400 font-bold py-3">Level</th>
                <th class="text-left text-xs uppercase text-gray-400 font-bold py-3">Gambar</th>
                <th class="text-left text-xs uppercase text-gray-400 font-bold py-3">Status</th>
                <th class="text-right text-xs uppercase text-gray-400 font-bold px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($exercises as $ex)
            <tr style="border-bottom:1px solid #1f1f1f;">
                <td class="px-5 py-3">
                    <p class="font-bold">{{ $ex->name }}</p>
                    @if($ex->description)
                        <p class="text-xs text-gray-500 mt-0.5" style="max-width:260px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
                            {{ $ex->description }}
                        </p>
                    @endif
                </td>
                <td class="py-3 text-gray-300">{{ $ex->muscleGroup->name }}</td>
                <td class="py-3">
                    <span class="text-xs font-bold px-2 py-0.5" style="background:#161616; border:1px solid #3f3f3f;">
                        {{ $equipLabels[$ex->equipment_type] ?? $ex->equipment_type }}
                    </span>
                </td>
                <td class="py-3">
                    <span class="text-xs font-bold" style="color:{{ $diffColor[$ex->difficulty] ?? '#fff' }};">
                        {{ ucfirst($ex->difficulty) }}
                    </span>
                </td>
                <td class="py-3">
                    @if($ex->illustration_path)
                        <img src="{{ asset('storage/'.$ex->illustration_path) }}"
                             class="object-cover" style="width:48px; height:48px; border:1px solid #2A2A2A;">
                    @else
                        <span class="text-xs text-gray-600">—</span>
                    @endif
                </td>
                <td class="py-3">
                    @if($ex->is_active)
                        <span class="text-xs font-bold" style="color:#FFD500;">Aktif</span>
                    @else
                        <span class="text-xs text-gray-500">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex gap-2 justify-end">
                        <a href="{{ route('admin.exercises.edit', $ex) }}" class="btn-outline text-xs">Edit</a>
                        <form action="{{ route('admin.exercises.destroy', $ex) }}" method="POST"
                              onsubmit="return confirm('Hapus gerakan {{ addslashes($ex->name) }}?')">
                            @csrf @method('DELETE')
                            <button class="btn-outline text-xs" style="border-color:#ef4444; color:#ef4444;">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $exercises->links() }}</div>

@endsection
