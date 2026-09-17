<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Work Report</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin: 0 0 5px 0;
        }

        .generated {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-bottom: 18px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            background: #e9ecef;
            border: 1px solid #999;
            padding: 7px;
            font-size: 11px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .label {
            width: 25%;
            font-weight: bold;
            background: #f8f8f8;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: middle;
        }

        .info-table .label {
            width: 18%;
            font-weight: bold;
            background: #f8f8f8;
        }

        .info-table td:not(.label) {
            width: 32%;
        }

        .text-box {
            border: 1px solid #aaa;
            padding: 8px;
            min-height: 35px;
            line-height: 1.4;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            font-weight: bold;
            border: 1px solid #777;
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

        .photo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .photo-cell {
            width: 50%;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
            padding: 8px;
        }

        .photo-cell img {
            width: 220px;
            max-height: 180px;
            object-fit: contain;
        }

        .photo-section {
            page-break-before: auto;
            page-break-inside: avoid;
        }

        .photo-table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }

        .no-border {
            border: none;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .header-logo {
            width: 33.33%;
            text-align: left;
            padding-top: 4px !important;
        }

        .header-logo img {
            width: 210px;
            height: auto;
        }

        .header-title {
            width: 33.34%;
            text-align: center;
            vertical-align: top;
        }

        .header-spacer {
            width: 33.33%;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.1;
            margin-top: 2px;
        }

        .generated {
            margin-top: 5px;
            font-size: 9px;
            color: #777;
        }

        .approval-status-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .approval-status-table td {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: middle;
        }

        .approval-table {
            width: 100%;
            border-collapse: collapse;
        }

        .approval-table td {
            border: 1px solid #aaa;
            padding: 8px;
            vertical-align: top;
        }

        .approval-column {
            width: 33.33%;
            text-align: left;
        }

        .approval-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .approval-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .approval-role {
            font-size: 9px;
            margin-bottom: 6px;
        }

        .approval-time {
            font-size: 9px;
            color: #555;
        }

        .approval-section {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    {{-- <h1>WORK REPORT</h1>

    <div class="generated">
        Generated at:
        {{ now()->format('d-m-Y H:i:s') }} WIB
    </div> --}}

    <table class="header-table">

        <tr>

            {{-- LOGO --}}
            <td class="header-logo">

                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/tbu.png'))) }}"
                    alt="Tata Bara Utama">

            </td>


            {{-- JUDUL --}}
            <td class="header-title">

                <div class="report-title">
                    WORK REPORT
                </div>

                <div class="generated">
                    Generated at:
                    {{ now()->format('d-m-Y H:i:s') }} WIB
                </div>

            </td>


            {{-- KOLOM KOSONG UNTUK MENJAGA CENTER --}}
            <td class="header-spacer"></td>

        </tr>

    </table>

    {{-- ========================================================= --}}
    {{-- INFORMASI PEKERJAAN --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Informasi Pekerjaan
        </div>

        <table class="info-table">

            <tr>
                <td class="label">
                    Tanggal
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($workreport->tanggal)->format('d-m-Y') }}
                </td>

                <td class="label">
                    Shift
                </td>

                <td>
                    {{ ucfirst($workreport->shift) }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Jam Mulai
                </td>

                <td>
                    {{ $workreport->jam_mulai }}
                </td>

                <td class="label">
                    Jam Berakhir
                </td>

                <td>
                    {{ $workreport->jam_berakhir }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Status
                </td>

                <td>
                    {{ ucfirst($workreport->status) }}
                </td>

                <td class="label">
                    Work Type
                </td>

                <td>
                    {{ ucfirst($workreport->work_type) }}
                </td>
            </tr>

        </table>

    </div>

    {{-- ========================================================= --}}
    {{-- INFORMASI PEKERJA --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Informasi Pekerja
        </div>

        <table>

            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th>Nama</th>
                    <th width="25%">NIK</th>
                    <th width="28%">Jabatan</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($workreport->members as $member)
                    <tr>
                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $member->nama }}
                        </td>

                        <td>
                            {{ $member->nik }}
                        </td>

                        <td>
                            {{ $member->jabatan }}
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>




    {{-- ========================================================= --}}
    {{-- INFORMASI UNIT --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Informasi Unit
        </div>

        <table class="info-table">

            <tr>
                <td class="label">
                    Nomor Unit
                </td>

                <td>
                    {{ $workreport->nomor_unit }}
                </td>

                <td class="label">
                    Component
                </td>

                <td>
                    {{ $workreport->component ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    HM Unit
                </td>

                <td>
                    {{ $workreport->hm_unit }}
                </td>

                <td class="label">
                    No. WO
                </td>

                <td>
                    {{ $workreport->no_wo ?: '-' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- ========================================================= --}}
    {{-- TROUBLE --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Trouble
        </div>

        <div class="text-box">
            {!! nl2br(e($workreport->trouble)) !!}
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- ACTIVITY --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Activity
        </div>

        <div class="text-box">
            {!! nl2br(e($workreport->activity)) !!}
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- CONTINUE NOTE --}}
    {{-- ========================================================= --}}

    @if ($workreport->status == 'continue')
        <div class="section">

            <div class="section-title">
                Keterangan Continue
            </div>

            <div class="text-box">
                {!! nl2br(e($workreport->continue_note)) !!}
            </div>

        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- APPROVAL --}}
    {{-- ========================================================= --}}

    <div class="section approval-section">

        <div class="section-title">
            Approval
        </div>

        {{-- STATUS VERIFIKASI --}}
        <table class="approval-status-table">

            <tr>

                <td class="label">
                    Status Verifikasi
                </td>

                <td>
                    {{ $workreport->approval_status ?: '-' }}
                </td>

            </tr>

        </table>


        {{-- DETAIL APPROVAL --}}
        <table class="approval-table">

            <tr>

                {{-- CREATE BY --}}
                <td class="approval-column">

                    <div class="approval-title">
                        Create By
                    </div>

                    @if ($workreport->user)
                        <div class="approval-name">
                            {{ $workreport->user->name }}
                        </div>

                        <div class="approval-role">
                            {{ $workreport->user->role }}
                        </div>

                        <div class="approval-time">
                            {{ $workreport->created_at->format('d-m-Y H:i') }}
                        </div>
                    @else
                        <div>-</div>
                    @endif

                </td>


                {{-- REVIEWED BY --}}
                <td class="approval-column">

                    <div class="approval-title">
                        Reviewed By
                    </div>

                    @if ($workreport->reviewer && $workreport->reviewed_at)
                        <div class="approval-name">
                            {{ $workreport->reviewer->name }}
                        </div>

                        <div class="approval-role">
                            {{ $workreport->reviewer->role }}
                        </div>

                        <div class="approval-time">
                            {{ $workreport->reviewed_at->format('d-m-Y H:i') }}
                        </div>
                    @else
                        <div>-</div>
                    @endif

                </td>


                {{-- APPROVED BY --}}
                <td class="approval-column">

                    <div class="approval-title">
                        Approved By
                    </div>

                    @if (
                        $workreport->approver &&
                            $workreport->approved_at &&
                            $workreport->approval_status === \App\Models\WorkReport::STATUS_APPROVED)
                        <div class="approval-name">
                            {{ $workreport->approver->name }}
                        </div>

                        <div class="approval-role">
                            {{ $workreport->approver->role }}
                        </div>

                        <div class="approval-time">
                            {{ $workreport->approved_at->format('d-m-Y H:i') }}
                        </div>
                    @elseif (
                        $workreport->approval_status === \App\Models\WorkReport::STATUS_FINAL_APPROVAL_REJECTED ||
                            $workreport->approval_status === \App\Models\WorkReport::STATUS_FOREMAN_REJECTED)
                        <div>-</div>
                    @else
                        <div>-</div>
                    @endif

                </td>

            </tr>

        </table>


        {{-- REJECTION REASON --}}
        @if ($workreport->rejection_reason)
            <table class="approval-status-table">

                <tr>

                    <td class="label">
                        Rejection Reason
                    </td>

                    <td>
                        {!! nl2br(e($workreport->rejection_reason)) !!}
                    </td>

                </tr>

            </table>
        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- FOTO --}}
    {{-- ========================================================= --}}

    @if ($workreport->photos->isNotEmpty())

        <div class="section photo-section">

            <div class="section-title">
                Foto Kegiatan
            </div>

            <table class="photo-table">

                @foreach ($workreport->photos->chunk(2) as $photoRow)
                    <tr>

                        @foreach ($photoRow as $photo)
                            <td class="photo-cell">

                                <img src="{{ public_path('storage/foto_kegiatan/' . $photo->file_path) }}">

                            </td>
                        @endforeach

                        {{-- Jika jumlah foto ganjil --}}
                        @if ($photoRow->count() == 1)
                            <td class="photo-cell"></td>
                        @endif

                    </tr>
                @endforeach

            </table>

        </div>

    @endif



</body>

</html>
