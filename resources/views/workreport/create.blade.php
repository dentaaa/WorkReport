@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Data Work Report</h4>
                <div>
                    <a href="{{ route('workreport.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('workreport.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="form-group my-2">
                        <label for="ama">Nama</label>
                        <input type="text" name="nama" id="nama"
                            class="form-control @error('nama')
                            is-invalid
                        @enderror"
                            value="{{ old('nama') }}" autofocus>
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
                            value="{{ old('nik') }}">
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
                            value="{{ old('jabatan') }}">
                        @error('jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            Anggota Pekerja
                        </h5>

                        <button type="button" class="btn btn-success btn-sm" id="btn-add-member">

                            <i class="ti ti-plus"></i> Tambah Anggota

                        </button>
                    </div>

                    <div id="member-wrapper"></div>

                    <div class="form-group my-2">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            class="form-control @error('tanggal')
                            is-invalid
                        @enderror"
                            value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="nomor_unit">Nomor Unit</label>

                        <select name="nomor_unit" id="nomor_unit"
                            class="form-control @error('nomor_unit') is-invalid @enderror">

                            <option value="">Pilih Nomor Unit</option>

                            @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ old('nomor_unit') == $unit ? 'selected' : '' }}>

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
                            value="{{ old('hm_unit') }}">
                        @error('hm_unit')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="component">
                            Component
                        </label>

                        <select name="component" id="component" class="form-control">

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
                                    {{ old('component') == $component ? 'selected' : '' }}>
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
                            value="{{ old('no_wo') }}">
                        @error('no_wo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="trouble">Trouble</label>
                        <textarea name="trouble" id="trouble" cols="30" rows="10"
                            class="form-control @error('trouble')
                            is-invalid
                        @enderror">{{ old('trouble') }}</textarea>
                        @error('trouble')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="activity">Activity</label>
                        <textarea name="activity" id="activity" cols="30" rows="10"
                            class="form-control @error('activity')
                            is-invalid
                        @enderror">{{ old('activity') }}</textarea>
                        @error('activity')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <label for="photos">Foto Kegiatan (Max 2 MB per foto)</label>
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

                        <div id="preview-container" class="row mt-2"></div>
                    </div>
                    <div class="form-group my-2">
                        <label for="shift">Shift</label>
                        <select name="shift" id="shift"
                            class="form-control @error('shift')
                            is-invalid
                        @enderror">
                            <option value="">Pilih Shift</option>
                            <option value="day" {{ old('shift') == 'day' ? 'selected' : '' }}>Day</option>
                            <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Night</option>
                        </select>
                    </div>
                    <div class="form-group my-2">
                        <label for="status">Status</label>
                        <select name="status" id="status"
                            class="form-control @error('status')
                            is-invalid
                        @enderror">
                            <option value="">Pilih Status</option>
                            <option value="ready" {{ old('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="continue" {{ old('status') == 'continue' ? 'selected' : '' }}>Continue</option>
                        </select>
                    </div>
                    <div id="continue-wrapper"
                        style="{{ old('status') == 'continue' || $errors->has('continue_note') ? '' : 'display:none;' }}">
                        <textarea name="continue_note" id="continue_note" class="form-control @error('continue_note') is-invalid @enderror"
                            rows="4" placeholder="Masukkan keterangan status Continue...">{{ old('continue_note') }}</textarea>

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
                            value="{{ old('jam_mulai') }}"> --}}
                        <input type="text" name="jam_mulai" id="jam_mulai"
                            class="form-control time-picker @error('jam_mulai') is-invalid @enderror"
                            value="{{ old('jam_mulai') }}" autocomplete="off">
                    </div>
                    <div class="form-group my-2">
                        <label for="jam_berakhir">Jam Berakhir</label>
                        {{-- <input type="time" name="jam_berakhir" id="jam_berakhir"
                            class="form-control @error('jam_berakhir')
                            is-invalid
                        @enderror"
                            value="{{ old('jam_berakhir') }}"> --}}
                        <input type="text" name="jam_berakhir" id="jam_berakhir"
                            class="form-control time-picker @error('jam_berakhir') is-invalid @enderror"
                            value="{{ old('jam_berakhir') }}" autocomplete="off">
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

                <button type="button" class="btn btn-outline-danger btn-sm remove-member">

                    <i class="ti ti-trash"></i>
                    Hapus

                </button>

            </div>

            <div class="card-body">

                <div class="mb-2">

                    <label class="form-label">
                        Nama
                    </label>

                    <input type="text" class="form-control member-nama" data-field="nama">

                    <small class="text-danger member-error-nama"></small>

                </div>

                <div class="mb-2">

                    <label class="form-label">
                        NIK
                    </label>

                    <input type="number" class="form-control member-nik" data-field="nik">

                    <small class="text-danger member-error-nik"></small>

                </div>

                <div>

                    <label class="form-label">
                        Jabatan
                    </label>

                    <input type="text" class="form-control member-jabatan" data-field="jabatan">

                    <small class="text-danger member-error-jabatan"></small>

                </div>

            </div>

        </div>

    </template>
    {{-- <script>
        const input = document.getElementById('photos');
        const previewContainer = document.getElementById('preview-container');
        const errorMessage = document.getElementById('error-message');

        let selectedFiles = [];

        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            errorMessage.innerText = '';

            // VALIDASI JUMLAH
            if (files.length + selectedFiles.length > 7) {
                errorMessage.innerText = 'Maksimal 7 foto';
                input.value = '';
                return;
            }

            files.forEach(file => {

                // VALIDASI TIPE
                if (!file.type.startsWith('image/')) {
                    errorMessage.innerText = 'Semua file harus berupa gambar';
                    return;
                }

                // VALIDASI SIZE (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    errorMessage.innerText = 'Ukuran maksimal 2MB';
                    return;
                }

                selectedFiles.push(file);

                const reader = new FileReader();

                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.classList.add('col-4', 'mb-2', 'preview-box');

                    col.innerHTML = `
                <img src="${e.target.result}" class="img-fluid preview-img">
                <button class="remove-btn">&times;</button>
            `;

                    // REMOVE BUTTON
                    col.querySelector('.remove-btn').addEventListener('click', () => {
                        previewContainer.removeChild(col);
                        selectedFiles = selectedFiles.filter(f => f !== file);
                        updateInputFiles();
                    });

                    previewContainer.appendChild(col);
                };

                reader.readAsDataURL(file);
            });

            updateInputFiles();
        });

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
        }
    </script> --}}

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

                const oldMembers = @json(old('members', []));

                const validationErrors = @json($errors->toArray());

                let index = 1;

                new TomSelect("#nomor_unit", {
                    create: true,
                    persist: false,
                    maxItems: 1,
                    hideSelected: true,
                    openOnFocus: true,
                    createOnBlur: true,
                    closeAfterSelect: true,
                });

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

                btnAdd.addEventListener("click", function() {

                    createMember();

                });

                wrapper.addEventListener("click", function(e) {

                    if (!e.target.classList.contains("remove-member"))
                        return;

                    e.target.closest(".member-item").remove();

                    updateTitle();

                    updateRemoveButton();

                });

                if (oldMembers.length > 0) {

                    oldMembers.forEach(member => {

                        createMember(member);

                    });

                } else {

                    createMember();

                }
            });
        </script>
    @endpush
@endsection
{{--
@push('scripts')
@endpush --}}
