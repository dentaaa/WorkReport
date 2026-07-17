@extends('layouts.mantis')

@php
    $isOwner = auth()->id() === $workreport->user_id;
    $isAdminEditingOthers = auth()->user()->isAdmin() && !$isOwner;
@endphp

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Work Report</h4>
                <div>
                    <a href="{{ route('workreport.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('workreport.update', $workreport->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- @if ($workreport->status_verifikasi != 'pending')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('input, textarea, select, button').forEach(el => {
                                    el.disabled = true;
                                });
                            });
                        </script>
                    @endif --}}
                    {{-- @if ($isAdminEditingOthers)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {

                                // Disable semua field
                                document.querySelectorAll('input, textarea, select').forEach(function(el) {
                                    el.disabled = true;
                                });

                                // Aktifkan kembali No. WO
                                document.querySelector('input[name="no_wo"]').disabled = false;

                            });
                        </script>
                    @endif --}}
                    {{-- <div class="form-group my-2">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama"
                            class="form-control @error('nama')
                            is-invalid
                        @enderror"
                            value="{{ $workreport->nama }}" autofocus @disabled($isAdminEditingOthers)>
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik"
                            class="form-control @error('nik')
                            is-invalid
                        @enderror"
                            value="{{ $workreport->nik }}" readonly>
                        @error('nik')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan"
                            class="form-control @error('jabatan')
                            is-invalid
                        @enderror"
                            value="{{ $workreport->jabatan }}" @disabled($isAdminEditingOthers)>
                        @error('jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}
                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            Anggota Pekerja
                        </h5>

                        {{-- <button type="button" class="btn btn-success btn-sm" id="btn-add-member">

                            <i class="ti ti-plus"></i> Tambah Anggota

                        </button> --}}

                        @if (!$isAdminEditingOthers)
                            <button type="button" class="btn btn-success btn-sm" id="btn-add-member">
                                <i class="ti ti-plus"></i>
                                Tambah Anggota
                            </button>
                        @endif
                    </div>

                    <div id="member-wrapper"></div>

                    <div class="form-group my-2">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            class="form-control @error('tanggal')
                            is-invalid
                        @enderror"
                            value="{{ old('tanggal', \Carbon\Carbon::parse($workreport->tanggal)->format('Y-m-d')) }}"
                            @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                            </div>
                        <div class="form-group my-2">
                            <label for="nomor_unit">Nomor Unit</label>

                            {{-- <select name="nomor_unit" id="nomor_unit"
                                class="form-control @error('nomor_unit') is-invalid @enderror" @disabled($isAdminEditingOthers)>

                                <option value="">Pilih Nomor Unit</option>

                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}"
                                        {{ old('nomor_unit', $workreport->nomor_unit) == $unit ? 'selected' : '' }}>

                                        {{ $unit }}

                                    </option>
                                @endforeach

                            </select> --}}
                            <select name="nomor_unit" id="nomor_unit"
                                class="form-control @error('nomor_unit') is-invalid @enderror" @disabled($isAdminEditingOthers)>

                                <option value="">Pilih Nomor Unit</option>

                                {{-- Jika nomor unit di database belum ada di master --}}
                                @if ($workreport->nomor_unit && !$units->contains($workreport->nomor_unit))
                                    <option value="{{ $workreport->nomor_unit }}" selected>
                                        {{ $workreport->nomor_unit }}
                                    </option>
                                @endif

                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}"
                                        {{ old('nomor_unit', $workreport->nomor_unit) == $unit ? 'selected' : '' }}>
                                        {{ $unit }}
                                    </option>
                                @endforeach

                            </select>

                            @error('nomor_unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group my-2">
                            <label for="hm_unit">HM Unit</label>
                            <input type="number" name="hm_unit" id="hm_unit"
                                class="form-control @error('hm_unit')
                            is-invalid
                        @enderror"
                                value="{{ $workreport->hm_unit }}" @disabled($isAdminEditingOthers)>
                            @error('hm_unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group my-2">
                            <label for="component">
                                Component
                            </label>

                            <select name="component" id="component" class="form-control" @disabled($isAdminEditingOthers)>

                                <option value="">
                                    Pilih Component
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
                                        {{ old('component', $workreport->component) == $component ? 'selected' : '' }}>
                                        {{ $component }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group my-2">
                            <label for="no_wo">No. WO</label>
                            <input type="text" name="no_wo" id="no_wo"
                                class="form-control @error('no_wo')
                            is-invalid
                        @enderror"
                                value="{{ $workreport->no_wo }}">
                            @error('no_wo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group my-2">
                            <label for="trouble">Trouble</label>
                            <textarea name="trouble" id="trouble" cols="30" rows="10"
                                class="form-control @error('trouble')
                            is-invalid
                        @enderror"
                                @disabled($isAdminEditingOthers)>{{ $workreport->trouble }}</textarea>
                            @error('trouble')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group my-2">
                            <label for="activity">Activity</label>
                            <textarea name="activity" id="activity" cols="30" rows="10"
                                class="form-control @error('activity')
                            is-invalid
                        @enderror"
                                @disabled($isAdminEditingOthers)>{{ $workreport->activity }}</textarea>
                            @error('activity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @if (!$isAdminEditingOthers)
                            <div class="form-group my-2">
                                <label for="photos">Tambah Foto Kegiatan (Max 2 MB per foto)</label>
                                {{-- <input type="file" name="foto" id="foto"
                            class="form-control @error('activity')
                            is-invalid
                        @enderror">
                        @error('foto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror --}}

                                <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                    class="form-control {{ $errors->has('photos') || $errors->has('photos.*') ? 'is-invalid' : '' }}">

                                <small id="error-message" class="text-danger"></small>

                                @error('photos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                @error('photos.*')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group my-3">
                            <label>Preview Foto</label>

                            <div id="preview-container" class="row">

                                @foreach ($workreport->photos as $photo)
                                    <div class="col-4 mb-3 text-center existing-photo">
                                        <img src="{{ asset('storage/foto_kegiatan/' . $photo->file_path) }}"
                                            class="img-fluid rounded mb-2 preview-img">

                                        @if (!$isAdminEditingOthers)
                                            <button type="button" onclick="deleteExistingPhoto({{ $photo->id }}, this)"
                                                class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="form-group my-2">
                            <label for="shift">Shift</label>
                            <select name="shift" id="shift"
                                class="form-control @error('shift')
                            is-invalid
                        @enderror"
                                @disabled($isAdminEditingOthers)>
                                <option value="">Pilih Shift</option>
                                <option value="day" {{ $workreport->shift == 'day' ? 'selected' : '' }}>Day</option>
                                <option value="night" {{ $workreport->shift == 'night' ? 'selected' : '' }}>Night</option>
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <label for="status">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status')
                            is-invalid
                        @enderror"
                                @disabled($isAdminEditingOthers)>
                                <option value="">Pilih Status</option>
                                <option value="ready" {{ $workreport->status == 'ready' ? 'selected' : '' }}>Ready
                                </option>
                                <option value="continue" {{ $workreport->status == 'continue' ? 'selected' : '' }}>
                                    Continue
                                </option>
                            </select>
                        </div>
                        <div id="continue-wrapper"
                            style="{{ $workreport->status == 'continue' || old('status') == 'continue' || $errors->has('continue_note') ? '' : 'display:none;' }}">

                            <label>Keterangan Continue</label>

                            <textarea name="continue_note" class="form-control @error('continue_note') is-invalid @enderror" rows="4"
                                placeholder="Masukkan keterangan lanjut pekerjaan..." @disabled($isAdminEditingOthers)>{{ old('continue_note', $workreport->continue_note) }}</textarea>

                            @error('continue_note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group my-2">
                            <label for="jam_mulai">Jam Mulai</label>
                            {{-- <input type="time" name="jam_mulai" id="jam_mulai"
                            class="form-control @error('jam_mulai')
                            is-invalid
                        @enderror"
                            value="{{ $workreport->jam_mulai }}" readonly> --}}
                            <input type="text" name="jam_mulai" id="jam_mulai"
                                class="form-control time-picker @error('jam_mulai') is-invalid @enderror"
                                value="{{ old('jam_mulai', \Carbon\Carbon::parse($workreport->jam_mulai)->format('H:i')) }}"
                                @disabled($isAdminEditingOthers)>
                        </div>
                        <div class="form-group my-2">
                            <label for="jam_berakhir">Jam Berakhir</label>
                            {{-- <input type="time" name="jam_berakhir" id="jam_berakhir"
                            class="form-control @error('jam_berakhir')
                            is-invalid
                        @enderror"
                            value="{{ $workreport->jam_berakhir }}" readonly> --}}
                            <input type="text" name="jam_berakhir" id="jam_berakhir"
                                class="form-control time-picker @error('jam_berakhir') is-invalid @enderror"
                                value="{{ old('jam_berakhir', \Carbon\Carbon::parse($workreport->jam_berakhir)->format('H:i')) }}"
                                @disabled($isAdminEditingOthers)>
                        </div>
                        <div class="my-2 d-flex justify-content-end">
                            <button class="btn btn-primary">Submit Data</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <template id="member-template">

        <div class="card mb-3 member-item shadow-sm">

            <div class="card-header bg-light d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <i class="ti ti-users me-2 fs-5 text-primary"></i>

                    <strong class="member-title mb-0">
                        Anggota #1
                    </strong>

                </div>
                @if (!$isAdminEditingOthers)
                    <button type="button" class="btn btn-outline-danger btn-sm remove-member">

                        <i class="ti ti-trash"></i>
                        Hapus

                    </button>
                @endif
            </div>

            <div class="card-body">

                <div class="mb-2">

                    <label class="form-label">
                        Nama
                    </label>

                    <input type="text" class="form-control member-nama" data-field="nama"
                        {{ $isAdminEditingOthers ? 'readonly' : '' }}>

                    <small class="text-danger member-error-nama"></small>

                </div>

                <div class="mb-2">

                    <label class="form-label">
                        NIK
                    </label>

                    <input type="number" class="form-control member-nik" data-field="nik"
                        {{ $isAdminEditingOthers ? 'readonly' : '' }}>

                    <small class="text-danger member-error-nik"></small>

                </div>

                <div>

                    <label class="form-label">
                        Jabatan
                    </label>

                    <input type="text" class="form-control member-jabatan" data-field="jabatan"
                        {{ $isAdminEditingOthers ? 'readonly' : '' }}>

                    <small class="text-danger member-error-jabatan"></small>

                </div>

            </div>

        </div>

    </template>

    <script>
        function deleteExistingPhoto(id, button) {
            if (!confirm('Yakin hapus foto ini?')) return;

            fetch(`/photo/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                button.closest('.existing-photo').remove();
            });
        }
    </script>

    @php
        $existingMembers = old(
            'members',
            $workreport->members
                ->map(function ($member) {
                    return [
                        'nama' => $member->nama,
                        'nik' => $member->nik,
                        'jabatan' => $member->jabatan,
                    ];
                })
                ->values()
                ->toArray(),
        );
    @endphp

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                flatpickr(".time-picker", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                    allowInput: true
                });

            });

            // new TomSelect("#nomor_unit", {
            //     create: true,
            //     persist: false,
            //     maxItems: 1,
            // });


            document.addEventListener("DOMContentLoaded", function() {

                const wrapper = document.getElementById("member-wrapper");

                const template = document.getElementById("member-template");

                const btnAdd = document.getElementById("btn-add-member");

                const existingMembers = @json($existingMembers);

                console.log(existingMembers);

                const validationErrors = @json($errors->toArray());

                let index = 1;

                const select = document.getElementById("nomor_unit");

                const ts = new TomSelect(select, {
                    create: true,
                    persist: false,
                    maxItems: 1,
                    hideSelected: true,
                    openOnFocus: true,
                    createOnBlur: true,
                    closeAfterSelect: true,
                });

                ts.setValue(select.value, true);

                function updateTitle() {

                    wrapper.querySelectorAll(".member-item").forEach((card, index) => {

                        card.querySelector(".member-title").innerHTML =
                            `Anggota #${index + 1}`;

                        card.querySelectorAll("[data-field]").forEach(input => {
                            input.name = `members[${index}][${input.dataset.field}]`;
                        });

                    });

                }

                function updateRemoveButton() {

                    const cards = wrapper.querySelectorAll(".member-item");

                    cards.forEach(card => {

                        const btn = card.querySelector(".remove-member");

                        if (!btn) return;

                        if (cards.length === 1) {
                            btn.style.display = "none";
                        } else {
                            btn.style.display = "";
                        }

                    });

                }

                function createMember(data = {}) {

                    const clone = template.content.cloneNode(true);

                    const card = clone.querySelector(".member-item");

                    card.querySelector(".member-nama").value =
                        data.nama ?? "";

                    card.querySelector(".member-nik").value =
                        data.nik ?? "";

                    card.querySelector(".member-jabatan").value =
                        data.jabatan ?? "";

                    const currentIndex = wrapper.querySelectorAll(".member-item").length;

                    ["nama", "nik", "jabatan"].forEach(field => {

                        const key = `members.${currentIndex}.${field}`;

                        if (validationErrors[key]) {

                            const input = card.querySelector(`.member-${field}`);

                            input.classList.add("is-invalid");

                            card.querySelector(`.member-error-${field}`).textContent =
                                validationErrors[key][0];

                        }

                    });

                    wrapper.appendChild(clone);

                    updateTitle();

                    updateRemoveButton();

                }

                if (btnAdd) {
                    btnAdd.addEventListener("click", function() {
                        createMember();
                    });
                }

                wrapper.addEventListener("click", function(e) {

                    const btn = e.target.closest(".remove-member");

                    if (!btn) return;

                    btn.closest(".member-item").remove();

                    updateTitle();

                    updateRemoveButton();

                });

                if (existingMembers.length > 0) {

                    existingMembers.forEach(member => {

                        createMember(member);

                    });

                } else {

                    createMember();

                }
            });
        </script>
    @endpush

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            const status = document.getElementById('status');
            const wrapper = document.getElementById('continue-wrapper');

            // sync saat load
            if (status.value === 'continue') {
                wrapper.style.display = 'block';
            }

            // handle change
            status.addEventListener('change', function() {
                wrapper.style.display = this.value === 'continue' ? 'block' : 'none';
            });

            // focus ke error
            const errorField = document.querySelector('.is-invalid');
            if (errorField) {
                errorField.focus();
                errorField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script> --}}

    {{-- @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                flatpickr(".time-picker", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                    allowInput: true
                });

            });
        </script>
    @endpush --}}
@endsection
