@extends('layouts.app')
@section('title', 'Progress: '.$exercise->name)

@section('content')
<h1 class="text-3xl font-black uppercase mb-2">{{ $exercise->name }}</h1>
<p class="text-gray-400 mb-8 text-sm">{{ $exercise->description }}</p>

<div class="card-flat p-6 mb-8">
    <h2 class="font-black uppercase mb-4" style="color:#FFD500;">Progressive Overload</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 uppercase text-xs border-b" style="border-color:#2A2A2A;">
                <th class="py-2">Tanggal</th>
                <th>Beban Maks</th>
                <th>Total Volume (beban x reps)</th>
                <th>Jumlah Set</th>
            </tr>
        </thead>
        <tbody>
        @foreach($logs as $row)
            <tr class="border-b" style="border-color:#1f1f1f;">
                <td class="py-2 font-bold">{{ $row['date'] }}</td>
                <td>{{ $row['max_weight'] ?? '-' }}</td>
                <td>{{ $row['total_volume'] }}</td>
                <td>{{ $row['sets'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($logs->isEmpty())
        <p class="text-gray-500">Belum ada data tracking untuk gerakan ini.</p>
    @endif
</div>

<a href="{{ route('workout.history') }}" class="btn-outline text-sm">&larr; Kembali ke Riwayat</a>
@endsection
