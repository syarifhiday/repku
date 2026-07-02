@extends('layouts.app')
@section('title', 'Riwayat Latihan')

@section('content')
<h1 class="text-3xl font-black uppercase mb-8">Riwayat Latihan</h1>

<div class="space-y-6">
@forelse($sessions as $session)
    <div class="card-flat p-0 overflow-hidden">
        {{-- Header sesi --}}
        <div class="flex justify-between items-center px-6 py-4" style="background:#1a1a1a; border-bottom:2px solid #2A2A2A;">
            <div>
                <p class="font-black">{{ $session->session_date->format('d M Y') }}</p>
                <p class="text-xs uppercase text-gray-400 mt-0.5">
                    {{ $session->enrollment?->program->name ?? 'Sesi Custom' }}
                    &middot; Hari {{ $session->day_number }}
                    &middot; {{ ucfirst($session->location) }}
                    @if($session->duration_minutes)
                        &middot; {{ $session->duration_minutes }} menit
                    @endif
                </p>
            </div>
            <span class="text-xs font-bold uppercase px-3 py-1" style="background:#2A2A2A;">
                {{ $session->logs->groupBy('exercise_id')->count() }} gerakan
            </span>
        </div>

        {{-- Tabel per gerakan --}}
        @foreach($session->logs->groupBy('exercise_id') as $exerciseLogs)
        @php
            $exercise = $exerciseLogs->first()->exercise;
            $isBand = $exerciseLogs->first()->weight_unit === 'band_level';
        @endphp
        <div class="px-6 py-4" style="border-bottom:1px solid #1f1f1f;">
            {{-- Nama gerakan --}}
            <a href="{{ route('workout.progress', $exercise->slug) }}"
               class="font-black text-sm hover:underline" style="color:#FFD500;">
                {{ $exercise->name }}
            </a>
            <span class="text-xs text-gray-500 ml-2 uppercase">{{ $exercise->muscleGroup->name }}</span>

            {{-- Tabel set --}}
            <div class="mt-3">
                <div class="grid text-xs uppercase text-gray-500 font-bold pb-1 mb-1"
                     style="grid-template-columns:60px 1fr 1fr 1fr; border-bottom:1px solid #2A2A2A;">
                    <span>Set</span>
                    <span>{{ $isBand ? 'Jml. Karet' : 'Beban' }}</span>
                    <span>Reps</span>
                    <span>Kondisi</span>
                </div>
                @foreach($exerciseLogs->sortBy('set_number') as $log)
                <div class="grid text-sm py-1"
                     style="grid-template-columns:60px 1fr 1fr 1fr;">
                    <span class="text-gray-400 font-bold">Set {{ $log->set_number }}</span>
                    <span class="font-bold">
                        @if($isBand)
                            {{ $log->weight_value ?? '-' }} karet
                        @else
                            {{ $log->weight_value ?? '-' }} kg
                        @endif
                    </span>
                    <span>{{ $log->reps }} reps</span>
                    <span class="text-gray-400">
                        @if($log->rpe)
                            @php $rpeLabel = match(true) { $log->rpe <= 4 => '😌 Mudah', $log->rpe <= 7 => '💪 Sedang', default => '🔥 Failure' }; @endphp
                            {{ $rpeLabel }}
                        @else — @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Catatan sesi --}}
        @if($session->notes)
        <div class="px-6 py-3 text-xs text-gray-400" style="background:#0f0f0f;">
            📝 {{ $session->notes }}
        </div>
        @endif
    </div>
@empty
    <p class="text-gray-500">Belum ada riwayat latihan.</p>
@endforelse
</div>

<div class="mt-8">{{ $sessions->links() }}</div>

@endsection
