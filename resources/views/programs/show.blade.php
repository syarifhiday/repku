@extends('layouts.app')
@section('title', $program->name)

@section('content')

@php
$locationIcons = ['gym' => '🏋️ Gym', 'rumah' => '🏠 Rumah', 'keduanya' => '🏋️🏠 Gym & Rumah'];
$equipmentLabels = [
    'barbell' => 'Barbell', 'dumbell' => 'Dumbbell', 'cable' => 'Cable',
    'mesin_gym' => 'Mesin Gym', 'resistance_band' => 'Resistance Band',
    'pull_up_bar' => 'Pull-Up Bar', 'kettlebell' => 'Kettlebell', 'bodyweight' => 'Tanpa Alat',
];
$diffColor = ['pemula' => '#22c55e', 'menengah' => '#FFD500', 'lanjutan' => '#ef4444'];
@endphp

{{-- Cover --}}
@if($program->cover_image_path)
<div class="mb-6 w-full overflow-hidden" style="max-height:280px; border:3px solid #2A2A2A;">
    <img src="{{ asset('storage/'.$program->cover_image_path) }}"
         alt="{{ $program->name }}"
         class="w-full object-cover" style="max-height:280px;">
</div>
@endif

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-black uppercase mb-2">{{ $program->name }}</h1>
        <p class="text-gray-400 text-sm leading-relaxed max-w-2xl">{{ $program->description }}</p>
    </div>
    <form action="{{ route('programs.enroll', $program->slug) }}" method="POST" class="ml-6 flex-shrink-0">
        @csrf
        <button class="btn-primary">Enroll Sekarang</button>
    </form>
</div>

{{-- Meta badges --}}
<div class="flex flex-wrap gap-2 mb-8">
    <span class="text-xs font-bold px-3 py-1" style="background:#FFD500; color:#0A0A0A;">
        {{ $program->sessions_per_week }}x / Minggu
    </span>
    <span class="text-xs font-bold px-3 py-1" style="background:#2A2A2A; color:#fff;">
        {{ $program->duration_weeks }} Minggu
    </span>
    <span class="text-xs font-bold px-3 py-1" style="background:#2A2A2A; color:#fff;">
        {{ $locationIcons[$program->location_type] ?? $program->location_type }}
    </span>
    @foreach(($program->equipment_tags ?? []) as $tag)
        <span class="text-xs font-bold px-3 py-1" style="background:#161616; border:1px solid #3f3f3f;">
            {{ $equipmentLabels[$tag] ?? $tag }}
        </span>
    @endforeach
</div>

{{-- Jadwal per hari --}}
<h2 class="text-lg font-black uppercase mb-4" style="color:#FFD500;">Jadwal Latihan</h2>

@foreach($exercisesByDay as $day => $items)
<div class="card-flat mb-4 overflow-hidden">
    {{-- Day Header --}}
    <div class="px-6 py-3 flex justify-between items-center" style="background:#1a1a1a; border-bottom:2px solid #2A2A2A;">
        <h3 class="font-black uppercase">Hari {{ $day }}</h3>
        <span class="text-xs text-gray-400 uppercase">{{ $items->count() }} gerakan</span>
    </div>

    {{-- Exercise list --}}
    <div class="divide-y" style="border-color:#1f1f1f;">
        @foreach($items as $pe)
        <div class="px-6 py-4 flex items-start gap-4">
            {{-- Ilustrasi --}}
            @if($pe->exercise->illustration_path)
                <img src="{{ asset('storage/'.$pe->exercise->illustration_path) }}"
                     alt="{{ $pe->exercise->name }}"
                     class="flex-shrink-0 object-cover"
                     style="width:72px; height:72px; border:2px solid #2A2A2A; background:#0A0A0A;">
            @else
                <div class="flex-shrink-0 flex items-center justify-center text-xs text-gray-600 font-bold"
                     style="width:72px; height:72px; border:2px solid #2A2A2A; background:#0A0A0A;">
                    No Img
                </div>
            @endif

            {{-- Info --}}
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-black">{{ $pe->exercise->name }}</p>
                        <p class="text-xs text-gray-400 uppercase mt-0.5">
                            {{ $pe->exercise->muscleGroup->name }}
                            &middot; {{ str_replace('_',' ', $pe->exercise->equipment_type) }}
                        </p>
                    </div>
                    <div class="text-right ml-4 flex-shrink-0">
                        <p class="font-black text-sm" style="color:#FFD500;">
                            {{ $pe->target_sets }} set × {{ $pe->target_reps }} reps
                        </p>
                        <span class="text-xs font-bold" style="color:{{ $diffColor[$pe->exercise->difficulty] ?? '#fff' }};">
                            {{ ucfirst($pe->exercise->difficulty) }}
                        </span>
                    </div>
                </div>
                @if($pe->exercise->description)
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $pe->exercise->description }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

{{-- CTA bawah --}}
<div class="mt-8 flex gap-4">
    <form action="{{ route('programs.enroll', $program->slug) }}" method="POST">
        @csrf
        <button class="btn-primary">Enroll ke Program Ini</button>
    </form>
    <a href="{{ route('programs.index') }}" class="btn-outline">← Kembali</a>
</div>

@endsection