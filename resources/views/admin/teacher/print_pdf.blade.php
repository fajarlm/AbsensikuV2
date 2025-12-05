<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Guru - {{ now()->format('d-m-Y') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 4px double #0d6efd;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }
        .header h2 {
            margin: 8px 0 0;
            font-size: 18px;
            color: #333;
        }
        .info-box {
            margin: 15px 0;
            font-size: 11px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
            border: none;
        }
        .info-box td {
            border: none;
            padding: 3px 5px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.data-table th, 
        table.data-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .text-center { 
            text-align: center; 
        }
        .foto {
            width: 40px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .no-foto {
            width: 40px;
            height: 50px;
            background: #e9ecef;
            border: 1px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #666;
            border-radius: 4px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-male {
            background: #cfe2ff;
            color: #084298;
        }
        .badge-female {
            background: #f7d6e6;
            color: #842029;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        .no { width: 30px; }
        .foto-col { width: 50px; }
        .nama { width: 150px; }
        .nip { width: 100px; }
        .jk { width: 70px; }
        .status { width: 70px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ config('app.name', 'SMKN 1') }}</h1>
        <h2>DAFTAR GURU</h2>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td style="width: 50%;"><strong>Total Guru:</strong> {{ count($teachers) }} orang</td>
                <td style="width: 50%; text-align: right;"><strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Guru Aktif:</strong> {{ collect($teachers)->where('status', 'active')->count() }} orang</td>
                <td style="text-align: right;"><strong>Guru Non-Aktif:</strong> {{ collect($teachers)->where('status', 'inactive')->count() }} orang</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="foto-col">Foto</th>
                <th class="nama">Nama Lengkap</th>
                <th>Username</th>
                <th class="nip">NIP</th>
                <th class="jk">Gender</th>
                <th class="status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $i => $teacher)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">
                        @if(isset($teacher['user']['profile']) && $teacher['user']['profile'])
                            <img src="{{ public_path('storage/' . $teacher['user']['profile']) }}" 
                                 alt="foto" class="foto">
                        @else
                            <div class="no-foto">No Photo</div>
                        @endif
                    </td>
                    <td><strong>{{ $teacher['user']['name'] }}</strong></td>
                    <td>{{ $teacher['user']['username'] }}</td>
                    <td class="text-center">{{ $teacher['nip'] ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $teacher['user']['gender'] }}">
                            {{ $teacher['user']['gender'] == 'male' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $teacher['status'] }}">
                            {{ $teacher['status'] == 'active' ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 30px; color: #999;">
                        Tidak ada data guru
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0;">
                    <strong>Dicetak oleh:</strong> {{ auth()->user()->name ?? 'Admin' }}
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <strong>Tanggal:</strong> {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p style="margin: 0;">Kepala Sekolah</p>
            <div class="signature-line">
                <strong>( ____________________ )</strong>
            </div>
        </div>
    </div>

</body>
</html>