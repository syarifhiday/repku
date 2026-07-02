@extends('layouts.app')
@section('title', 'Admin — Program')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-black uppercase">Kelola Program Preset</h1>
        <p class="text-gray-400 text-sm mt-1">Total {{ $programs->total() }} program</p>
    </div>
    <a href="{{ route('admin.programs.create') }}" class="btn-primary text-sm">+ Buat Program</a>
</div>

@php
$locationIcons = ['gym' => '🏋️ Gym', 'rumah' => '🏠 Rumah', 'keduanya' => '🏋️🏠'];
$equipLabels = [
    'barbell' => 'Barbell', 'dumbell' => 'Dumbbell', 'cable' => 'Cable',
    'mesin_gym' => 'Mesin', 'resistance_band' => 'Band', 'pull_up_bar' => 'Pull-Up Bar',
    'kettlebell' => 'Kettlebell', 'bodyweight' => 'Tanpa Alat',
];
$goalLabels = \App\Models\Program::goalLabels();
@endphp

<div class="space-y-3">
@foreach($programs as $program)
<div class="card-flat p-0 overflow-hidden flex">
    {{-- Cover thumbnail --}}
    @if($program->cover_image_path)
        <img src="{{ asset('storage/'.$program->cover_image_path) }}"
             class="object-cover flex-shrink-0" style="width:100px; min-height:80px;">
    @else
        <div class="flex-shrink-0 flex items-center justify-center font-black text-2xl"
             style="width:100px; min-height:80px; background:#0f0f0f; color:#2A2A2A;">
            🎯
        </div>
    @endif

    {{-- Info --}}
    <div class="flex-1 px-5 py-4 flex items-center justify-between gap-4 min-w-0">
        <div class="min-w-0">
            <p class="font-black truncate">{{ $program->name }}</p>
            <p class="text-xs text-gray-500 uppercase mt-0.5">
                {{ $goalLabels[$program->goal] ?? $program->goal }}
                &middot; {{ $program->sessions_per_week }}x/minggu
                &middot; {{ $program->duration_weeks }} minggu
            </p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="text-xs px-2 py-0.5 font-bold" style="background:#161616; border:1px solid #3f3f3f;">
                    {{ $locationIcons[$program->location_type] ?? '-' }}
                </span>
                @foreach(($program->equipment_tags ?? []) as $tag)
                    <span class="text-xs px-2 py-0.5 font-bold" style="background:#161616; border:1px solid #3f3f3f;">
                        {{ $equipLabels[$tag] ?? $tag }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            @if($program->is_active)
                <span class="text-xs font-bold" style="color:#FFD500;">Aktif</span>
            @else
                <span class="text-xs text-gray-500">Nonaktif</span>
            @endif
            <a href="{{ route('programs.show', $program->slug) }}" class="btn-outline text-xs">Lihat</a>
            <a href="{{ route('admin.programs.edit', $program) }}" class="btn-outline text-xs">Edit</a>
            <form action="{{ route('admin.programs.destroy', $program) }}" method="POST"
                  onsubmit="return confirm('Hapus program ini?')">
                @csrf @method('DELETE')
                <button class="btn-outline text-xs" style="border-color:#ef4444; color:#ef4444;">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endforeach
</div>

<div class="mt-6">{{ $programs->links() }}</div>

@endsection