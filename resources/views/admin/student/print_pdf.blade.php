<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 4px double #000;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }
        .header h2 {
            margin: 8px 0 0;
            font-size: 18px;
            color: #555;
        }
        .info {
            margin: 15px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 8px 6px;
            border: 1px solid #333;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #333;
            vertical-align: top;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .foto {
            width: 40px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .status-active    { background: #d4edda; color: #155724; }
        .status-inactive  { background: #f8d7da; color: #721c24; }
        .status-graduated { background: #d1ecf1; color: #0c5460; }
        .status-dropped   { background: #fff3cd; color: #856404; }
        .footer {
            margin-top: 60px;
            text-align: right;
            font-size: 11px;
        }
        .no { width: 35px; }
        .foto-col { width: 55px; }
        .kelas { width: 90px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMK NEGERI 1 CONTOH</h1>
        <h2>DAFTAR SISWA</h2>
        <div class="info">
            Dicetak pada: {{ $tanggal_cetak }}<br>
            Total Siswa: {{ $total_siswa }} orang
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="foto-col">Foto</th>
                <th>Nama Lengkap</th>
                <th>NIS</th>
                <th>Username</th>
                <th>Jenis Kelamin</th>
                <th class="kelas">Kelas</th>
                <th>Tahun Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $i => $student)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if($student->user->profile && Storage::disk('public')->exists($student->user->profile))
                            <img src="{{ public_path('storage/' . $student->user->profile) }}" 
                                 alt="foto" class="foto">
                        @else
                            <div style="width:40px;height:50px;background:#eee;border:1px dashed #aaa;
                                        display:flex;align-items:center;justify-content:center;font-size:9px;">
                                No Photo
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $student->user->name }}</strong></td>
                    <td class="text-center">{{ $student->nis }}</td>
                    <td>{{ $student->user->username }}</td>
                    <td class="text-center">
                        {{ $student->user->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                    <td class="text-center">
                        @if($student->studyGroup)
                            {{ $student->studyGroup->grade }}
                            {{ $student->studyGroup->major }}
                            {{ $student->studyGroup->class_number }}
                        @else
                            <em>-</em>
                        @endif
                    </td>
                    <td class="text-center">{{ $student->entry_year }}</td>
                    <td class="text-center">
                        @php
                            $statusText = [
                                'active'      => 'Aktif',
                                'inactive'    => 'Tidak Aktif',
                                'graduated'   => 'Lulus',
                                'dropped_out' => 'Keluar'
                            ];
                            $statusClass = [
                                'active'      => 'status-active',
                                'inactive'    => 'status-inactive',
                                'graduated'   => 'status-graduated',
                                'dropped_out' => 'status-dropped'
                            ];
                        @endphp
                        <span class="status-badge {{ $statusClass[$student->status] ?? 'status-active' }}">
                            {{ $statusText[$student->status] ?? ucfirst($student->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Administrator' }}</p>
        <div style="margin-top: 70px; float: right; width: 220px; text-align: center;">
            <p>_________________________</p>
            <p>( {{ auth()->user()->name ?? 'Admin' }} )</p>
        </div>
        <div style="clear:both;"></div>
    </div>

</body>
</html>