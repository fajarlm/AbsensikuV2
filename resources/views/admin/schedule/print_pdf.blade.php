@foreach ($groups as $g)

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran {{ $g->grade }} {{ $g->major }} {{ $g->class_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; margin: 15px; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 4px double #0d6efd; }
        .header h1 { margin: 0; font-size: 28px; color: #0d6efd; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 2px solid #333; padding: 8px; text-align: center; vertical-align: middle; height: 70px; }
        th { background: #0d6efd; color: white; }
        .time { background: #e3f2fd; font-weight: bold; width: 13%; }
        .subject div {
            background: #4361ee; color: white; border-radius: 6px; padding: 6px;
            height: 100%; display: flex; flex-direction: column; justify-content: center;
        }
        .empty { color: #999; }
        .footer { margin-top: 50px; text-align: right; font-size: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1>JADWAL PELAJARAN</h1>
        <h2 style="margin:10px 0; font-size:22px">
            {{ html_entity_decode($g->grade . ' ' . $g->major . ' ' . $g->class_number) }}
        </h2>
    </div>

    <div style="margin:15px 0; display:flex; justify-content:space-between; font-size:12px">
        <div><strong>Jumlah Siswa:</strong> {{ $g->students->count() }} orang</div>
        <div><strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }}</div>
    </div>

    {{-- IMPORTANT: Tidak mengubah logic jadwal lo sama sekali --}}
    <table>
        <thead>
            <tr>
                <th>Jam</th>
                <th>Senin</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $index => $start)
                @php
                    $end = $timeSlots[$index + 1] ?? 'Selesai';
                @endphp
                <tr>
                    <td class="time">
                        {{ $start }} - {{ $end !== 'Selesai' ? $end : 'Selesai' }}
                    </td>

                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $day)
                        @php
                            $jadwal = $g->schedules->first(fn($s) =>
                                $s->day === $day && $s->start_time->format('H:i') === $start
                            );
                            $color = $jadwal ? substr(md5($jadwal->subject->name), 0, 6) : '666';
                            $guru  = $jadwal?->subject?->teacher?->user?->name ?? '';
                        @endphp

                        <td>
                            @if($jadwal)
                                <div style="background:#{{ $color }}">
                                    <div style="font-weight:bold">{{ $jadwal->subject->code }}</div>
                                    <div>{{ html_entity_decode($jadwal->subject->name) }}</div>
                                    <small>{{ html_entity_decode($guru) }}</small>
                                </div>
                            @else
                                <span class="empty">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }} • {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>

@if (!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach
