<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Work Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1 {
            text-align: center;
            margin-bottom: 0;
        }

        .generated {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        .label {
            width: 180px;
            font-weight: bold;
        }

        .content-box {
            border: 1px solid #ddd;
            padding: 10px;
            white-space: pre-wrap;
            min-height: 50px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
        }

        .approved {
            background: #d1fae5;
        }

        .pending {
            background: #fef3c7;
        }

        .rejected {
            background: #fee2e2;
        }

        .photo-grid {
            width: 100%;
        }

        .photo-item {
            width: 48%;
            display: inline-block;
            margin-bottom: 10px;
            text-align: center;
        }

        .photo-item img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <h1>WORK REPORT</h1>

    <div class="generated">
        Generated at:
        {{ now()->format('d-m-Y H:i:s') }} WIB
    </div>

    <div class="section">
        <div class="section-title">
            Informasi Mekanik
        </div>

        <table>
            <tr>
                <td class="label">Nama</td>
                <td>{{ $workreport->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>{{ $workreport->nik }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>{{ $workreport->jabatan }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>
                    {{ \Carbon\Carbon::parse($workreport->tanggal)->format('d-m-Y') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            Informasi Unit
        </div>

        <table>
            <tr>
                <td class="label">Nomor Unit</td>
                <td>{{ $workreport->nomor_unit }}</td>
            </tr>
            <tr>
                <td class="label">HM Unit</td>
                <td>{{ $workreport->hm_unit }}</td>
            </tr>
            <tr>
                <td class="label">Component</td>
                <td>{{ $workreport->component ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No WO</td>
                <td>{{ $workreport->no_wo }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            Pekerjaan
        </div>

        <strong>Trouble</strong>
        <div class="content-box">{{ $workreport->trouble }}</div>

        <br>

        <strong>Activity</strong>
        <div class="content-box">{{ $workreport->activity }}</div>

        @if ($workreport->status == 'continue')
            <br>

            <strong>Continue Note</strong>
            <div class="content-box">{{ $workreport->continue_note }}</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">
            Informasi Shift
        </div>

        <table>
            <tr>
                <td class="label">Shift</td>
                <td>{{ ucfirst($workreport->shift) }}</td>
            </tr>

            <tr>
                <td class="label">Status</td>
                <td>{{ ucfirst($workreport->status) }}</td>
            </tr>

            <tr>
                <td class="label">Jam Mulai</td>
                <td>{{ $workreport->jam_mulai }}</td>
            </tr>

            <tr>
                <td class="label">Jam Berakhir</td>
                <td>{{ $workreport->jam_berakhir }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            Status Verifikasi
        </div>

        <span class="status {{ $workreport->status_verifikasi }}">
            {{ ucfirst($workreport->status_verifikasi) }}
        </span>
    </div>

    <div class="section">
        <div class="section-title">
            Foto Kegiatan
        </div>

        <div class="photo-grid">
            @foreach ($workreport->photos as $photo)
                <div class="photo-item">
                    <img src="{{ public_path('storage/foto_kegiatan/' . $photo->file_path) }}">
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
