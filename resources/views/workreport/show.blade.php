@extends('layouts.mantis')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">

            {{-- @php
                $statusClass = match ($workreport->approval_status) {
                    \App\Models\WorkReport::STATUS_APPROVED => 'bg-success',

                    \App\Models\WorkReport::STATUS_AWAITING_FINAL_APPROVAL => 'bg-info',

                    \App\Models\WorkReport::STATUS_FOREMAN_REJECTED,
                    \App\Models\WorkReport::STATUS_FINAL_APPROVAL_REJECTED
                        => 'bg-danger',

                    \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW => 'bg-warning text-dark',

                    default => 'bg-secondary',
                };
            @endphp --}}

            <h4>
                Detail Work Report

                {{-- <span class="badge {{ $statusClass }}">
                    {{ $workreport->approval_status }}
                </span> --}}
                <x-approval-status-badge :status="$workreport->approval_status" />
            </h4>

            {{-- <h4>
                Detail Work Report
                <span
                    class="
            @if ($workreport->status_verifikasi == 'approved') badge text-bg-success
            @elseif($workreport->status_verifikasi == 'rejected')
                badge text-bg-danger
            @else
                badge text-bg-warning @endif
        ">
                    {{ ucfirst($workreport->status_verifikasi) }}
                </span>
            </h4> --}}

            <div class="d-flex gap-2">

                @if (auth()->user()->isAdmin() ||
                        auth()->user()->isForeman() ||
                        auth()->user()->isDeptHead() ||
                        auth()->user()->isSupervisor())
                    <a href="{{ route('workreport.pdf', $workreport->id) }}" class="btn btn-danger">
                        Download PDF
                    </a>
                @endif

                <a href="{{ route('workreport.index') }}" class="btn btn-secondary">

                    Kembali
                </a>

            </div>

        </div>

        <div class="card-body">

            {{-- <p><strong>Nama:</strong> {{ $workreport->nama }}</p>
            <p><strong>NIK:</strong> {{ $workreport->nik }}</p>
            <p><strong>Jabatan:</strong> {{ $workreport->jabatan }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($workreport->tanggal)->format('d-m-Y') }}</p>
            <p>
                <strong>Jam Mulai:</strong>
                {{ \Carbon\Carbon::parse($workreport->jam_mulai)->format('H:i') }}
            </p>

            <p>
                <strong>Jam Berakhir:</strong>
                {{ \Carbon\Carbon::parse($workreport->jam_berakhir)->format('H:i') }}
            </p>
            <p><strong>Nomor Unit:</strong> {{ $workreport->nomor_unit }}</p>
            <p><strong>HM unit:</strong> {{ $workreport->hm_unit }}</p>
            <p><strong>Component:</strong> {{ $workreport->component ?? '-' }}</p>
            <p><strong>No. WO :</strong> {{ $workreport->no_wo }}</p>

            <div class="field-group">
                <strong>Activity:</strong>
                <div class="field-text">
                    {!! nl2br(e($workreport->activity)) !!}
                </div>
            </div>

            <div class="field-group">
                <strong>Trouble:</strong>
                <div class="field-text">
                    {!! nl2br(e($workreport->trouble)) !!}
                </div>
            </div>

            <p><strong>Status:</strong> {{ $workreport->status }}</p>

            @if ($workreport->status == 'continue')
                <div class="field-group">
                    <strong>Keterangan Continue:</strong>
                    <div class="field-text">
                        {!! nl2br(e($workreport->continue_note)) !!}
                    </div>
                </div>
            @endif

            <hr> --}}

            {{-- <div class="card mb-3">
                <div class="card-header">
                    <strong>Informasi Pekerja</strong>
                </div>

                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Nama</strong></div>
                        <div class="col-md-9">: {{ $workreport->nama }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>NIK</strong></div>
                        <div class="col-md-9">: {{ $workreport->nik }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Jabatan</strong></div>
                        <div class="col-md-9">: {{ $workreport->jabatan }}</div>
                    </div>
                </div>
            </div> --}}

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Informasi Pekerja</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">No</th>
                                    <th>Nama</th>
                                    <th width="180">NIK</th>
                                    <th width="220">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workreport->members as $member)
                                    <tr>
                                        <td class="text-center">
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
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Informasi Pekerjaan</strong>
                </div>

                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Tanggal</strong></div>
                        <div class="col-md-9">
                            : {{ \Carbon\Carbon::parse($workreport->tanggal)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Jam Mulai</strong></div>
                        <div class="col-md-9">
                            : {{ \Carbon\Carbon::parse($workreport->jam_mulai)->format('H:i') }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Jam Berakhir</strong></div>
                        <div class="col-md-9">
                            : {{ \Carbon\Carbon::parse($workreport->jam_berakhir)->format('H:i') }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Shift</strong></div>
                        <div class="col-md-9">
                            : {{ ucfirst($workreport->shift) }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Status</strong></div>
                        <div class="col-md-9">
                            :
                            @if ($workreport->status == 'ready')
                                <span class="badge bg-success px-3 py-2 fs-6">
                                    Ready
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                    Continue
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Informasi Unit</strong>
                </div>

                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Nomor Unit</strong></div>
                        <div class="col-md-9">
                            : {{ $workreport->nomor_unit }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>HM Unit</strong></div>
                        <div class="col-md-9">
                            : {{ $workreport->hm_unit }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Component</strong></div>
                        <div class="col-md-9">
                            : {{ $workreport->component ?? '-' }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>No. WO</strong></div>
                        <div class="col-md-9">
                            : {{ $workreport->no_wo ?: '-' }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Activity</strong>
                </div>

                <div class="card-body">
                    {!! nl2br(e($workreport->activity)) !!}
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Trouble</strong>
                </div>

                <div class="card-body">
                    {!! nl2br(e($workreport->trouble)) !!}
                </div>
            </div>

            @if ($workreport->status == 'continue')
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Keterangan Continue</strong>
                    </div>

                    <div class="card-body">
                        {!! nl2br(e($workreport->continue_note)) !!}
                    </div>
                </div>
            @endif

            <h5>Foto Kegiatan</h5>

            <div class="row">
                @foreach ($workreport->photos as $photo)
                    <div class="col-4 mb-3">
                        <a href="{{ asset('storage/foto_kegiatan/' . $photo->file_path) }}" data-lightbox="workreport"
                            data-title="Foto Kegiatan">

                            <img src="{{ asset('storage/foto_kegiatan/' . $photo->file_path) }}"
                                class="img-fluid rounded shadow-sm" style="cursor:pointer;">
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="card mt-4">

                <div class="card-header">
                    <strong>Approval</strong>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label class="font-weight-bold">
                                Status
                            </label>

                            <div>

                                {{-- <span class="badge {{ $statusClass }}">
                                    {{ $workreport->approval_status }}
                                </span> --}}
                                <x-approval-status-badge :status="$workreport->approval_status" />

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="font-weight-bold">
                                Reviewed By
                            </label>

                            <div>

                                @if ($workreport->reviewer)
                                    <strong>{{ $workreport->reviewer->name }}</strong><br>

                                    {{ $workreport->reviewer->role }}<br>

                                    {{ optional($workreport->reviewed_at)->format('d-m-Y H:i') }}
                                @else
                                    Waiting for Foreman Review
                                @endif

                            </div>

                        </div>

                        @if ($workreport->rejection_reason)
                            <hr>

                            <div class="mb-3">

                                <strong class="text-danger">
                                    <i class="fa fa-exclamation-circle"></i>
                                    Rejection Reason

                                </strong>

                                <div class="alert alert-danger mt-2 mb-0">

                                    {!! nl2br(e($workreport->rejection_reason)) !!}

                                </div>

                            </div>
                        @endif

                        <div class="col-md-6">

                            <label class="font-weight-bold">
                                Approved By
                            </label>

                            <div>

                                @if (
                                    $workreport->approval_status == \App\Models\WorkReport::STATUS_FOREMAN_REJECTED ||
                                        $workreport->approval_status == \App\Models\WorkReport::STATUS_FINAL_APPROVAL_REJECTED)
                                    <span class="text-muted">

                                        Approval process stopped

                                    </span>
                                @elseif($workreport->approver && $workreport->approved_at)
                                    {{ $workreport->approver->name }}<br>
                                    {{ $workreport->approver->role }}<br>
                                    {{ $workreport->approved_at->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-muted">
                                        Waiting for Final Approval
                                    </span>
                                @endif

                            </div>

                        </div>

                        <hr>

                        @if (
                            (auth()->user()->isForeman() &&
                                $workreport->approval_status == \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW) ||
                                ((auth()->user()->isSupervisor() || auth()->user()->isDeptHead()) &&
                                    $workreport->approval_status == \App\Models\WorkReport::STATUS_AWAITING_FINAL_APPROVAL) ||
                                auth()->user()->isAdmin())
                            <div class="text-center">
                                @php

                                    if (
                                        $workreport->approval_status ==
                                        \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
                                    ) {
                                        $approveRoute = route('workreport.foreman.approve', $workreport);

                                        $rejectRoute = route('workreport.foreman.reject', $workreport);
                                    } else {
                                        $approveRoute = route('workreport.final.approve', $workreport);

                                        $rejectRoute = route('workreport.final.reject', $workreport);
                                    }

                                @endphp
                                <button id="btnReviewReport" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#approvalActionModal" data-approve-route="{{ $approveRoute }}"
                                    data-reject-route="{{ $rejectRoute }}">

                                    Review Report

                                </button>

                                {{-- <div id="review-panel" style="display:none;" class="mt-4">
                                    @if (auth()->user()->isForeman())
                                        <form action="{{ route('workreport.foreman.approve', $workreport) }}"
                                            method="POST">

                                            @csrf

                                            <button class="btn btn-success">

                                                Approve

                                            </button>

                                        </form>
                                    @endif
                                </div> --}}

                            </div>
                        @endif

                    </div>
                </div>

            </div>
            {{-- @if (auth()->user()->isForeman() || auth()->user()->isAdmin())
                <h5>Status Verifikasi</h5>
                <form action="{{ route('workreport.updateStatus', $workreport->id) }}" method="POST">
                    @csrf
                    <select name="status_verifikasi" class="form-control mb-1">
                        <option value="pending" {{ $workreport->status_verifikasi == 'pending' ? 'selected' : '' }}>
                            Pending</option>
                        <option value="approved" {{ $workreport->status_verifikasi == 'approved' ? 'selected' : '' }}>
                            Approved</option>
                        <option value="rejected" {{ $workreport->status_verifikasi == 'rejected' ? 'selected' : '' }}>
                            Rejected</option>
                    </select>
                    <button class="btn btn-success">Update Status</button>
                </form>
            @endif --}}

        </div>
    </div>

    <div class="modal fade" id="approvalActionModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form id="approvalForm" method="POST">

                    @csrf
                    {{-- <input type="hidden" id="approvalRoute" value=""> --}}

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Review Work Report
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Current Status
                            </label>

                            {{-- <input class="form-control" value="{{ $workreport->approval_status }}" readonly> --}}
                            <x-approval-status-badge :status="$workreport->approval_status" />

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Action
                            </label>

                            <div>

                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="action" id="approveAction"
                                        value="approve" checked>

                                    <label class="form-check-label" for="approveAction">

                                        Approve

                                    </label>

                                </div>

                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="action" id="rejectAction"
                                        value="reject">

                                    <label class="form-check-label" for="rejectAction">

                                        Reject

                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="mb-3" id="reasonContainer" style="display:none;">

                            <label class="form-label">

                                Rejection Reason

                            </label>

                            <textarea class="form-control" rows="4" name="rejection_reason" id="rejectionReason"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button class="btn btn-primary">

                            Submit

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    @push('scripts')
        <script>
            if (typeof lightbox !== 'undefined') {
                lightbox.option({
                    'resizeDuration': 200,
                    'wrapAround': true,
                    'albumLabel': "Foto %1 dari %2"
                })
            }
        </script>
        <script src="{{ asset('js/workreport-approval.js') }}"></script>
    @endpush
@endsection
