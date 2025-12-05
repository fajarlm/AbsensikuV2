<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar {{ ucfirst($role ?: 'Semua User') }} - {{ now()->format('d-m-Y') }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            margin: 0; 
            padding: 30px; 
        }
        .container { 
            background: white; 
            padding: 20px; 
        }
        .header { 
            text-align: center; 
            border-bottom: 4px solid #0d6efd; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .header h1 { 
            color: #0d6efd; 
            margin: 0; 
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 12px; 
        }
        th { 
            background: #0d6efd; 
            color: white; 
            padding: 12px; 
            text-align: left; 
            font-weight: bold;
        }
        td { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
        }
        tr:nth-child(even) { 
            background: #f8f9fa; 
        }
        .text-center { 
            text-align: center; 
        }
        .badge { 
            padding: 4px 10px; 
            border-radius: 12px; 
            font-size: 10px; 
            color: white; 
            display: inline-block;
        }
        .badge-admin { 
            background: #dc3545; 
        }
        .badge-teacher { 
            background: #0d6efd; 
        }
        .badge-student { 
            background: #198754; 
        }
        .badge-male {
            background: #0dcaf0;
            color: #000;
        }
        .badge-female {
            background: #d63384;
        }
        .footer { 
            margin-top: 60px; 
            text-align: center; 
            color: #666; 
            font-size: 11px; 
            border-top: 2px solid #ddd;
            padding-top: 20px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DAFTAR {{ $role ? strtoupper($role) : 'SEMUA USER' }}</h1>
            <p>{{ config('app.name', 'SMKN 1') }} &bull; Tahun Ajaran {{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Lengkap</th>
                    <th width="15%">Username</th>
                    
                    @if($role == 'student')
                        <th width="12%">NIS</th>
                        <th width="18%">Kelas</th>
                    @elseif($role == 'teacher')
                        <th width="12%">NIP</th>
                        <th width="18%">Mata Pelajaran</th>
                    @else
                        <th width="12%">Role</th>
                    @endif
                    
                    <th width="12%">Gender</th>
                    <th width="13%">Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td><strong>{{ $user['name'] }}</strong></td>
                        <td>{{ $user['username'] }}</td>
                        
                        @if($role == 'student')
                            <td>{{ $user['student']['nis'] ?? '-' }}</td>
                            <td>
                                @if(isset($user['student']['study_group']))
                                    {{ $user['student']['study_group']['grade'] ?? '' }} 
                                    {{ $user['student']['study_group']['major'] ?? '' }} 
                                    {{ $user['student']['study_group']['class_number'] ?? '' }}
                                @else
                                    -
                                @endif
                            </td>
                        @elseif($role == 'teacher')
                            <td>{{ $user['teacher']['nip'] ?? '-' }}</td>
                            <td>
                                @if(isset($user['teacher']['subjects']) && count($user['teacher']['subjects']) > 0)
                                    {{ implode(', ', array_column($user['teacher']['subjects'], 'name')) }}
                                @else
                                    -
                                @endif
                            </td>
                        @else
                            <td class="text-center">
                                <span class="badge badge-{{ $user['role'] }}">
                                    {{ strtoupper($user['role']) }}
                                </span>
                            </td>
                        @endif
                        
                        <td class="text-center">
                            <span class="badge badge-{{ $user['gender'] }}">
                                {{ $user['gender'] == 'male' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($user['created_at'])) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">
                            Tidak ada data {{ $role ?: 'user' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>
                <strong>Total {{ $role ? ucfirst($role) : 'User' }}:</strong> {{ count($users) }} orang<br>
                <strong>Dicetak pada:</strong> {{ now()->translatedFormat('d F Y H:i') }} WIB<br>
                <strong>Dicetak oleh:</strong> {{ auth()->user()->name ?? 'Admin' }}
            </p>
        </div>
    </div>
</body>
</html>