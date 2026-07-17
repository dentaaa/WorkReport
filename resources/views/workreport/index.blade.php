@extends('layouts.mantis')

@section('content')
    <div class="container-fluid px-0">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data Work Report</h4>
                <div>
                    <a href="{{ route('workreport.create') }}" class="btn btn-primary">
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $hasFilter =
                        request()->filled('tanggal_awal') ||
                        request()->filled('tanggal_akhir') ||
                        request()->filled('nama') ||
                        request()->filled('nik') ||
                        request()->filled('jabatan') ||
                        request()->filled('nomor_unit') ||
                        request()->filled('no_wo') ||
                        request()->filled('component') ||
                        request()->filled('shift') ||
                        request()->filled('status') ||
                        request()->filled('approval_status') ||
                        request()->filled('trouble') ||
                        request()->filled('activity') ||
                        request()->filled('continue_note');
                @endphp
                <div class="mb-3">
                    <button id="advancedFilterBtn" class="btn btn-outline-primary" type="button">
                        Advanced Filter
                    </button>
                </div>

                <div class="collapse mb-4" id="advancedFilter">
                    <div class="card card-body">

                        <form method="GET" action="{{ route('workreport.index') }}">
                            <div class="row g-3">

                                {{-- ROW 1 --}}
                                <div class="col-md-3">
                                    <label>Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control"
                                        value="{{ request('tanggal_awal') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control"
                                        value="{{ request('tanggal_akhir') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ request('nama') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" value="{{ request('nik') }}">
                                </div>

                                {{-- ROW 2 --}}
                                <div class="col-md-3">
                                    <label>Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control"
                                        value="{{ request('jabatan') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Nomor Unit</label>

                                    <select name="nomor_unit" id="filter_nomor_unit">

                                        <option value="">
                                            Semua
                                        </option>

                                        @php
                                            $selectedUnit = request('nomor_unit');
                                        @endphp

                                        {{-- Jika user memfilter dengan unit custom --}}
                                        @if ($selectedUnit && !$units->contains($selectedUnit))
                                            <option value="{{ $selectedUnit }}" selected>
                                                {{ $selectedUnit }}
                                            </option>
                                        @endif

                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}"
                                                {{ $selectedUnit == $unit ? 'selected' : '' }}>

                                                {{ $unit }}

                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>No WO</label>
                                    <input type="text" name="no_wo" class="form-control"
                                        value="{{ request('no_wo') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Component</label>

                                    <select name="component" class="form-control">

                                        <option value="">
                                            Semua
                                        </option>

                                        @php
                                            $components = [
                                                'PM Service',
                                                'TA (Technical Analysis)',
                                                'Engine',
                                                'Fuel System',
                                                'Cooling System',
                                                'Air Conditioning',
                                                'Electrical System',
                                                'Swing',
                                                'Clutch / Converter',
                                                'Transmission',
                                                'Differential',
                                                'Final Drive',
                                                'Air & Brake System',
                                                'Axle',
                                                'Hydraulic System',
                                                'Steering System',
                                                'Suspension',
                                                'Attachment',
                                                'Bucket & Linkage',
                                                'Tyre & Rim',
                                                'Undercarriage',
                                                'Cabin',
                                                'Frame & Structure',
                                            ];
                                        @endphp

                                        @foreach ($components as $component)
                                            <option value="{{ $component }}"
                                                {{ request('component') == $component ? 'selected' : '' }}>
                                                {{ $component }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Shift</label>
                                    <select name="shift" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="day" {{ request('shift') == 'day' ? 'selected' : '' }}>
                                            Day</option>
                                        <option value="night" {{ request('shift') == 'night' ? 'selected' : '' }}>
                                            Night</option>
                                    </select>
                                </div>

                                {{-- ROW 3 --}}
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>
                                            Ready</option>
                                        <option value="continue" {{ request('status') == 'continue' ? 'selected' : '' }}>
                                            Continue</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Status Approval</label>
                                    <select name="approval_status" class="form-control">
                                        <option value="">Semua</option>

                                        <option value="{{ \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW }}"
                                            {{ request('approval_status') == \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW ? 'selected' : '' }}>
                                            Awaiting Foreman Review
                                        </option>

                                        <option value="{{ \App\Models\WorkReport::STATUS_AWAITING_FINAL_APPROVAL }}"
                                            {{ request('approval_status') == \App\Models\WorkReport::STATUS_AWAITING_FINAL_APPROVAL ? 'selected' : '' }}>
                                            Awaiting Final Approval
                                        </option>

                                        <option value="{{ \App\Models\WorkReport::STATUS_APPROVED }}"
                                            {{ request('approval_status') == \App\Models\WorkReport::STATUS_APPROVED ? 'selected' : '' }}>
                                            Approved
                                        </option>

                                        <option value="{{ \App\Models\WorkReport::STATUS_FOREMAN_REJECTED }}"
                                            {{ request('approval_status') == \App\Models\WorkReport::STATUS_FOREMAN_REJECTED ? 'selected' : '' }}>
                                            Foreman Rejected
                                        </option>

                                        <option value="{{ \App\Models\WorkReport::STATUS_FINAL_APPROVAL_REJECTED }}"
                                            {{ request('approval_status') == \App\Models\WorkReport::STATUS_FINAL_APPROVAL_REJECTED ? 'selected' : '' }}>
                                            Final Rejected
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Trouble</label>
                                    <input type="text" name="trouble" class="form-control"
                                        value="{{ request('trouble') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Activity</label>
                                    <input type="text" name="activity" class="form-control"
                                        value="{{ request('activity') }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Continue Note</label>
                                    <input type="text" name="continue_note" class="form-control"
                                        value="{{ request('continue_note') }}">
                                </div>

                                {{-- BUTTON --}}
                                <div class="col-12 mt-3 d-flex gap-2 flex-wrap">

                                    <button type="submit" class="btn btn-primary">
                                        Apply Filter
                                    </button>

                                    <a href="{{ route('workreport.index') }}" class="btn btn-secondary">
                                        Reset
                                    </a>

                                    @if (auth()->user()->isAdmin() || auth()->user()->isForeman())
                                        {{-- <a href="{{ route('workreport.export.csv', request()->query()) }}"
                                            class="btn btn-success">

                                            Export CSV
                                        </a> --}}

                                        <a href="{{ route('workreport.export.excel', request()->query()) }}"
                                            class="btn btn-info">

                                            Export Excel
                                        </a>
                                    @endif

                                </div>

                            </div>
                        </form>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered w-100 text-start" id="table">
                        <thead>
                            <tr>
                                <th class="text-start">Nama</th>
                                <th class="text-start">NIK</th>
                                <th class="text-start">Jabatan</th>
                                <th class="text-start">Tanggal</th>
                                <th class="text-start">Nomor Unit</th>
                                <th class="text-start">HM Unit</th>
                                {{-- <th>Trouble</th> --}}
                                {{-- <th>Activity</th> --}}
                                <th class="text-center">Foto Kegiatan</th>
                                <th class="text-start">Shift</th>
                                <th class="text-start">Status</th>
                                <th class="text-start">Jam Mulai</th>
                                <th class="text-start">Jam Berakhir</th>
                                <th class="text-center">Status Approval</th>
                                <th class="text-start">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workreport as $index => $item)
                                <tr>
                                    {{-- <td>{{ $index + 1 }}</td> --}}
                                    {{-- <td>{{ $item->nama }}</td> --}}
                                    <td>
                                        @if ($item->members->isNotEmpty())
                                            <strong>{{ $item->members->first()->nama }}</strong>

                                            @if ($item->members->count() > 1)
                                                <span class="text-muted">
                                                    (+{{ $item->members->count() - 1 }})
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-start align-middle">{{ $item->nik }}</td> --}}
                                    <td class="text-start align-middle">

                                        @if ($item->members->isNotEmpty())
                                            {{ $item->members->first()->nik }}
                                        @else
                                            -
                                        @endif

                                    </td>
                                    {{-- <td>{{ $item->jabatan }}</td> --}}
                                    <td>

                                        @if ($item->members->isNotEmpty())
                                            {{ $item->members->first()->jabatan }}
                                        @else
                                            -
                                        @endif

                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $item->nomor_unit }}</td>
                                    <td class="text-start align-middle">{{ $item->hm_unit }}</td>
                                    {{-- <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->trouble }}</td> --}}
                                    {{-- <td
                                        style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $item->activity }}</td> --}}
                                    <td class="text-center align-middle">
                                        {{-- @if ($item->foto_kegiatan)
                                            <img src="{{ Storage::url('foto_kegiatan/' . $item->foto_kegiatan) }}"
                                                alt="Foto Kegiatan" style="max-width: 100px; max-height: 100px;">
                                        @else
                                            <p class="mb-0">No Photo</p>
                                        @endif --}}

                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalFoto{{ $item->id }}">
                                            Lihat Foto
                                        </button>
                                    </td>
                                    <td>{{ $item->shift }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->jam_mulai }}</td>
                                    <td>{{ $item->jam_berakhir }}</td>
                                    {{-- <td class="text-center align-middle rounded-pill"> --}}
                                    {{-- <span
                                            class="
                                                @if ($item->status_verifikasi == 'approved') badge badge-status text-bg-success
                                                @elseif($item->status_verifikasi == 'rejected') badge badge-status text-bg-danger
                                                @else badge badge-status text-bg-warning @endif
                                            ">
                                            {{ ucfirst($item->status_verifikasi) }}
                                        </span> --}}
                                    <td class="text-center align-middle">

                                        <x-approval-status-badge :status="$item->approval_status" />

                                    </td>
                                    {{-- </td> --}}
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('workreport.show', $item->id) }}">
                                                        Lihat Detail
                                                    </a>
                                                </li>
                                                @if (auth()->user()->isAdmin() ||
                                                        auth()->user()->isForeman() ||
                                                        auth()->user()->isDeptHead() ||
                                                        auth()->user()->isSupervisor())
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('workreport.pdf', $item->id) }}">

                                                            Download PDF
                                                        </a>
                                                    </li>
                                                @endif
                                                {{-- <li>
                                                    @if (auth()->user()->isMekanik() && $item->status_verifikasi == 'pending')
                                                        <a class="dropdown-item"
                                                            href="{{ route('workreport.edit', $item->id) }}">Edit</a>
                                                    @endif
                                                </li> --}}
                                                {{-- @php
                                                    $user = auth()->user();

                                                    $canEdit =
                                                        ($item->user_id == $user->id &&
                                                            $item->status_verifikasi == 'pending') ||
                                                        $user->isAdmin();
                                                @endphp --}}
                                                @php
                                                    // use App\Models\WorkReport;

                                                    $user = auth()->user();

                                                    $canEdit =
                                                        ($item->user_id == $user->id &&
                                                            $item->approval_status ==
                                                                \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW) ||
                                                        $user->isAdmin();
                                                @endphp

                                                @if ($canEdit)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('workreport.edit', $item->id) }}">
                                                            Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                {{-- <li>
                                                    <button type="button" class="btn text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal{{ $item->id }}">
                                                        Delete Data
                                                    </button>
                                                </li> --}}
                                                @php
                                                    $canDelete =
                                                        $user->isAdmin() ||
                                                        ($item->user_id == $user->id &&
                                                            $item->approval_status ==
                                                                \App\Models\WorkReport::STATUS_AWAITING_FOREMAN_REVIEW);
                                                @endphp

                                                @if ($canDelete)
                                                    <li>
                                                        <button type="button" class="btn text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal{{ $item->id }}">
                                                            Delete Data
                                                        </button>
                                                    </li>
                                                @endif
                                                {{-- @if (auth()->user()->isForeman() || auth()->user()->isAdmin())
                                                    <form action="{{ route('workreport.updateStatus', $item->id) }}"
                                                        method="POST" class="mt-2">
                                                        @csrf
                                                        <select name="status_verifikasi" class="form-control mb-1">
                                                            <option value="pending"
                                                                {{ $item->status_verifikasi == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="approved"
                                                                {{ $item->status_verifikasi == 'approved' ? 'selected' : '' }}>
                                                                Approved</option>
                                                            <option value="rejected"
                                                                {{ $item->status_verifikasi == 'rejected' ? 'selected' : '' }}>
                                                                Rejected</option>
                                                        </select>
                                                        <button class="btn btn-sm btn-success">Update</button>
                                                    </form>
                                                @endif --}}
                                                {{-- <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($workreport as $item)
        <!-- Modal -->
        <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalLabel">Lanjutkan Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            {{-- <span aria-hidden="true">&times;</span> --}}
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>data akan dihapus secara permanen, Klik <b>Lanjutkan</b> untuk menghapus data</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>

                        <form action="{{ route('workreport.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Lanjutkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($workreport as $item)
        <div class="modal fade" id="modalFoto{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Foto Kegiatan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach ($item->photos as $photo)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/foto_kegiatan/' . $photo->file_path) }}"
                                    class="img-fluid mb-2">

                                {{-- <form action="{{ route('photo.delete', $photo->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form> --}}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filterElement =
                document.getElementById('advancedFilter');

            const filterButton =
                document.getElementById('advancedFilterBtn');

            const collapse =
                new bootstrap.Collapse(filterElement, {
                    toggle: false
                });

            const hasFilter = @json($hasFilter);

            const ts = new TomSelect("#filter_nomor_unit", {
                maxItems: 1,
                allowEmptyOption: true,
                placeholder: "Semua",
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc",
                },
            });

            ts.wrapper.classList.remove("form-control");

            // kalau ada filter aktif → buka
            if (hasFilter) {
                collapse.show();
            }

            // tombol manual toggle
            filterButton.addEventListener('click', function() {

                const isOpen =
                    filterElement.classList.contains('show');

                if (isOpen) {
                    collapse.hide();
                } else {
                    collapse.show();
                }

            });

        });
    </script>
@endsection
