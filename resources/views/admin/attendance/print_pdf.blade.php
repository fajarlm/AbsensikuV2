<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Siswa - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #f9f9f9;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 4px solid #0d6efd;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0d6efd;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #555;
        }
        .info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .info div {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th {
            background: #0d6efd;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-hadir { background: #d4edda; color: #155724; font-weight: bold; }
        .status-alfa { background: #f8d7da; color: #721c24; font-weight: bold; }
        .status-sakit { background: #fff3cd; color: #856404; font-weight: bold; }
        .status-izin { background: #d1ecf1; color: #0c5460; font-weight: bold; }
        .status-dibebaskan { background: #e2e3e5; color: #383d41; font-weight: bold; }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <h1>LAPORAN ABSENSI SISWA</h1>
            <p>Sistem Informasi Akademik Sekolah</p>
            <h2>{{ $date }}</h2>
        </div>

        <!-- Info Ringkasan -->
        <div class="info">
            <div><strong>Total Data Absensi:</strong>@php
                echo count($attendances);
            @endphp record</div>
            <div><strong>Tanggal Export:</strong> {{ now()->format('d F Y, H:i') }}</div>
            <div><strong>Dibuat oleh:</strong> Administrator</div>
        </div>

        <!-- Tabel Absensi -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="12%">Kelas</th>
                    <th width="20%">Nama Siswa</th>
                    <th width="18%">Mata Pelajaran</th>
                    <th width="10%">Status</th>
                    <th width="25%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $i => $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{\Carbon\Carbon::parse($a->attendance_date)->format('d F Y') }}</td>
                        <td>{{ $a->schedule->studyGroup->grade ?? '' }} {{ $a->schedule->studyGroup->major ?? '' }} {{ $a->schedule->studyGroup->class_number ?? '' }}</td>
                        <td>{{ $a->student->user->name }}</td>
                        <td>{{ $a->schedule->subject->name }}</td>
                        <td>
                            <span class="badge 
                                @if($a->status == 'present') status-hadir
                                @elseif($a->status == 'absent') status-alfa
                                @elseif($a->status == 'sick') status-sakit
                                @elseif($a->status == 'permission') status-izin
                                @else status-dibebaskan
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $a->status)) }}
                            </span>
                        </td>
                        <td>{{ $a->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#999;">
                            <em>Tidak ada data absensi untuk periode ini.</em>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Sistem Absensi Sekolah • Dicetak pada {{ now()->format('d F Y H:i') }}</p>
            <p>Laporan ini bersifat rahasia dan hanya untuk keperluan internal sekolah.</p>
        </div>
    </div>
</body>
</html>