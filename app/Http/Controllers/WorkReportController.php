<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\WorkReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\WorkReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\WorkReportMember;
use Illuminate\Support\Facades\DB;
use App\Services\WorkReport\ApprovalService;
use App\Models\User;
use App\Services\Notification\NotificationService;

// use Psy\Util\Str;

class WorkReportController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService = $notificationService;
    }

    private function filteredQuery(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = WorkReport::with([
            'photos',
            'members'
        ]);

        // Mekanik hanya lihat data sendiri
        // if ($user->isMekanik()) {
        //     $query->where('user_id', $user->id);
        // }

        // FILTER TANGGAL
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // TEXT FILTER (LIKE)
        $textFilters = [
            'nomor_unit',
            'no_wo',
            'trouble',
            'activity',
            'continue_note'
        ];

        // foreach ($textFilters as $field) {
        //     if ($request->filled($field)) {
        //         $query->where($field, 'like', '%' . $request->$field . '%');
        //     }
        //     if ($request->filled('nama')) {

        //         $query->whereHas('members', function ($q) use ($request) {

        //             $q->where('nama', 'like', '%' . $request->nama . '%');
        //         });
        //     }
        //     if ($request->filled('nik')) {

        //         $query->whereHas('members', function ($q) use ($request) {

        //             $q->where('nik', 'like', '%' . $request->nik . '%');
        //         });
        //     }
        //     if ($request->filled('jabatan')) {

        //         $query->whereHas('members', function ($q) use ($request) {

        //             $q->where('jabatan', 'like', '%' . $request->jabatan . '%');
        //         });
        //     }
        // }
        foreach ($textFilters as $field) {

            if ($request->filled($field)) {

                $query->where(
                    $field,
                    'like',
                    '%' . $request->$field . '%'
                );
            }
        }

        if ($request->filled('nama')) {

            $query->whereHas('members', function ($q) use ($request) {

                $q->where(
                    'nama',
                    'like',
                    '%' . $request->nama . '%'
                );
            });
        }

        if ($request->filled('nik')) {

            $query->whereHas('members', function ($q) use ($request) {

                $q->where(
                    'nik',
                    'like',
                    '%' . $request->nik . '%'
                );
            });
        }

        if ($request->filled('jabatan')) {

            $query->whereHas('members', function ($q) use ($request) {

                $q->where(
                    'jabatan',
                    'like',
                    '%' . $request->jabatan . '%'
                );
            });
        }

        // DROPDOWN FILTER
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {

            $query->where(
                'approval_status',
                $request->approval_status
            );
        }

        if ($request->filled('component')) {
            $query->where('component', $request->component);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $workreport = $query->latest()->get();

        $units = collect(config('units'))->pluck('code');

        return view('workreport.index', compact('workreport', 'units'));

        // /** @var \App\Models\User $user */
        // $user = Auth::user();

        // if ($user->isMekanik()) {
        //     $workreport = WorkReport::with('photos')
        //         ->where('user_id', $user->id)
        //         ->get();
        // } else {
        //     $workreport = WorkReport::with('photos')->get();
        // }

        // return view('workreport.index', compact('workreport'));
    }

    public function exportCsv(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isForeman()) {
            abort(403);
        }

        $query = $this->filteredQuery($request);

        $filename = 'work_report_' . now()->format('d-m-Y_H-i-s') . '.csv';

        return Excel::download(
            new WorkReportExport($query),
            $filename
        );
    }

    public function exportExcel(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isForeman()) {
            abort(403);
        }

        $query = $this->filteredQuery($request);

        $filename = 'work_report_' . now()->format('d-m-Y_H-i-s') . '.xlsx';

        return Excel::download(
            new WorkReportExport($query),
            $filename
        );
    }

    public function create()
    {
        $units = collect(config('units'))->pluck('code');
        return view('workreport.create', compact('units'));
    }

    public function store(Request $request)
    {
        // dd($request->members);

        $request->validate([
            // 'nama'  => 'required',
            // 'nik'  => 'required|integer|min:0|max:4294967295',
            // 'jabatan'  => 'required',
            'members' => 'required|array|min:1',
            'members.*.nama' => 'required|string|max:255',
            'members.*.nik' => 'required|integer|min:0|max:4294967295',
            'members.*.jabatan' => 'required|string|max:255',
            'tanggal'  => 'required|date',
            'nomor_unit'  => 'required|string',
            'hm_unit'  => 'required|integer|min:0|max:4294967295',
            'component' => 'nullable|in:PM Service,TA (Technical Analysis),Engine,Fuel System,Cooling System,Air Conditioning,Electrical System,Swing,Clutch / Converter,Transmission,Differential,Final Drive,Air & Brake System,Axle,Hydraulic System,Steering System,Suspension,Attachment,Bucket & Linkage,Tyre & Rim,Undercarriage,Cabin,Frame & Structure',
            'no_wo'  => 'nullable|string',
            'trouble'  => 'required',
            'activity'  => 'required',
            // 'foto'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'shift'  => 'required|in:day,night',
            'status'  => 'required|in:ready,continue',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_berakhir'  => 'required|date_format:H:i',
        ], [
            // 'nama.required' => 'Nama harus diisi.',
            // 'nik.required' => 'NIK harus diisi.',
            // 'jabatan.required' => 'Jabatan harus diisi.',
            'members.required' => 'Minimal harus ada 1 anggota.',
            'members.*.nama.required' => 'Nama anggota harus diisi.',
            'members.*.nik.required' => 'NIK anggota harus diisi.',
            'members.*.jabatan.required' => 'Jabatan anggota harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nomor_unit.required' => 'Nomor Unit harus diisi.',
            'hm_unit.required' => 'HM Unit harus diisi.',
            'no_wo.required' => 'No. WO harus diisi.',
            'trouble.required' => 'Trouble harus diisi.',
            'activity.required' => 'Activity harus diisi.',
            'shift.required' => 'Shift harus diisi.',
            'status.required' => 'Status harus diisi.',
            'jam_mulai.required' => 'Jam Mulai harus diisi.',
            'jam_berakhir.required' => 'Jam Berakhir harus diisi.',

        ]);

        // WorkReport::create([
        //     'nama' => $request->nama,
        //     'nik' => $request->nik,
        //     'jabatan' => $request->jabatan,
        //     'tanggal' => $request->tanggal,
        //     'nomor_unit' => $request->nomor_unit,
        //     'hm_unit' => $request->hm_unit,
        //     'trouble' => $request->trouble,
        //     'activity' => $request->activity,
        //     'shift' => $request->shift,
        //     'status' => $request->status,
        //     'jam_mulai' => $request->jam_mulai,
        //     'jam_berakhir' => $request->jam_berakhir,
        // ]);

        // $foto = $request->file('foto');
        // // $filename = Str::uuid() . '.' . $foto->getClientOriginalExtension();

        // $filename = Str::uuid() . '.' . $foto->getClientOriginalExtension();

        // // dd($filename);
        // Storage::disk('public')->putFileAs('foto_kegiatan', $foto, $filename);

        // $newRequest = $request->all();
        // $newRequest['foto_kegiatan'] = $filename;

        // WorkReport::create($newRequest);

        // $workreport = WorkReport::create($request->all());

        if ($request->status == 'continue' && empty($request->continue_note)) {
            return back()->withErrors([
                'continue_note' => 'Keterangan wajib diisi jika status Continue'
            ])->withInput();
        }

        $firstMember = $request->members[0];

        $workreport = WorkReport::create([

            ...$request->except('members'),

            'nama' => $firstMember['nama'],

            'nik' => $firstMember['nik'],

            'jabatan' => $firstMember['jabatan'],

            'user_id' => Auth::id(),

        ]);

        foreach ($request->members as $member) {

            $workreport->members()->create([

                'nama' => $member['nama'],

                'nik' => $member['nik'],

                'jabatan' => $member['jabatan'],

            ]);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('foto_kegiatan', $file, $filename);

                $workreport->photos()->create([
                    'file_path' => $filename
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kirim Notification ke semua Foreman
        |--------------------------------------------------------------------------
        */

        $foremen = User::where('role', 'Foreman')->get();

        foreach ($foremen as $foreman) {

            $this->notificationService->send(

                $foreman,

                'New Work Report',

                "Work Report #{$workreport->id} dari {$workreport->nama} menunggu review.",

                'review',

                $workreport,

                'fas fa-clipboard-list',

                route('workreport.show', $workreport)

            );
        }

        return redirect()->route('workreport.index')->with('success', 'Data Laporan Pekerjaan Berhasil Ditambahkan.');
    }

    public function edit(String $id)
    {
        // $workreport = WorkReport::find($id);
        $workreport = WorkReport::findOrFail($id);

        $units = collect(config('units'))->pluck('code');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        |
        | Admin boleh membuka semua halaman edit.
        | Nanti di Blade hanya field No. WO yang aktif
        | jika bukan report miliknya.
        |
        */

        if ($user->isAdmin()) {
            return view('workreport.edit', compact('workreport', 'units'));
        }

        /*
        |--------------------------------------------------------------------------
        | Selain Admin
        |--------------------------------------------------------------------------
        */

        if ($workreport->user_id != $user->id) {
            abort(403);
        }

        if ($workreport->status_verifikasi != 'pending') {
            abort(403, 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        // dd($workreport->nomor_unit);

        return view('workreport.edit', compact('workreport', 'units'));
    }

    public function updateStatus(Request $request, $id)
    {
        $workreport = WorkReport::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isForeman() && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:pending,approved,rejected'
        ]);

        $workreport->update([
            'status_verifikasi' => $request->status_verifikasi
        ]);

        return back()->with('success', 'Status berhasil diupdate');
    }

    public function update(Request $request, WorkReport $workreport)
    {
        // dd($workreport->toArray());
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (!$user->isAdmin()) {

            if ($workreport->user_id != $user->id) {
                abort(403);
            }

            if ($workreport->status_verifikasi != 'pending') {
                abort(403, 'Data yang sudah diverifikasi tidak dapat diubah.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Admin hanya boleh mengubah No. WO milik orang lain
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin() && $workreport->user_id != $user->id) {

            $request->validate([
                'no_wo' => 'nullable|string|max:255'
            ]);

            $workreport->update([
                'no_wo' => $request->no_wo
            ]);

            return redirect()
                ->route('workreport.index')
                ->with('success', 'Nomor WO berhasil diperbarui.');
        }

        $request->validate([
            // 'nama'  => 'required',
            // 'nik'  => 'required|numeric',
            // 'jabatan'  => 'required',
            'members' => 'required|array|min:1',
            'members.*.nama' => 'required|string|max:255',
            'members.*.nik' => 'required|integer|min:0|max:4294967295',
            'members.*.jabatan' => 'required|string|max:255',
            'tanggal'  => 'required|date',
            'nomor_unit'  => 'required|string',
            'hm_unit'  => 'required|numeric',
            'no_wo'  => 'nullable|string',
            'component' => 'nullable|in:PM Service,TA (Technical Analysis),Engine,Fuel System,Cooling System,Air Conditioning,Electrical System,Swing,Clutch / Converter,Transmission,Differential,Final Drive,Air & Brake System,Axle,Hydraulic System,Steering System,Suspension,Attachment,Bucket & Linkage,Tyre & Rim,Undercarriage,Cabin,Frame & Structure',
            'trouble'  => 'required',
            'activity'  => 'required',
            // 'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'shift'  => 'required|in:day,night',
            'status'  => 'required|in:ready,continue',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_berakhir'  => 'required|date_format:H:i',
        ], [
            // 'nama.required' => 'Nama harus diisi.',
            // 'nik.required' => 'NIK harus diisi.',
            // 'jabatan.required' => 'Jabatan harus diisi.',
            'members.required' => 'Minimal harus ada 1 anggota.',
            'members.*.nama.required' => 'Nama anggota harus diisi.',
            'members.*.nik.required' => 'NIK anggota harus diisi.',
            'members.*.jabatan.required' => 'Jabatan anggota harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'nomor_unit.required' => 'Nomor Unit harus diisi.',
            'hm_unit.required' => 'HM Unit harus diisi.',
            'no_wo.required' => 'No. WO harus diisi.',
            'trouble.required' => 'Trouble harus diisi.',
            'activity.required' => 'Activity harus diisi.',
            'shift.required' => 'Shift harus diisi.',
            'status.required' => 'Status harus diisi.',
            'jam_mulai.required' => 'Jam Mulai harus diisi.',
            'jam_berakhir.required' => 'Jam Berakhir harus diisi.',

        ]);

        // $workreport->update([
        //     'nama' => $request->nama,
        //     'nik' => $request->nik,
        //     'jabatan' => $request->jabatan,
        //     'tanggal' => $request->tanggal,
        //     'nomor_unit' => $request->nomor_unit,
        //     'hm_unit' => $request->hm_unit,
        //     'trouble' => $request->trouble,
        //     'activity' => $request->activity,
        //     'shift' => $request->shift,
        //     'status' => $request->status,
        //     'jam_mulai' => $request->jam_mulai,
        //     'jam_berakhir' => $request->jam_berakhir,
        // ]);

        // $workreport->update($request->all());

        // $fileName = $workreport->foto_kegiatan;
        // $foto = $request->file('foto');

        // if ($foto) {
        //     $filename = Str::uuid() . '.' . $foto->getClientOriginalExtension();
        //     Storage::disk('public')->putFileAs('foto_kegiatan', $foto, $filename);
        // } else {
        //     $filename = $workreport->foto_kegiatan;
        // }

        // if ($request->hasFile('foto')) {

        //     // upload dulu
        //     $filename = Str::uuid() . '.' . $request->foto->getClientOriginalExtension();
        //     Storage::disk('public')->putFileAs('foto_kegiatan', $request->foto, $filename);

        //     // baru hapus lama
        //     if ($workreport->foto_kegiatan) {
        //         Storage::disk('public')->delete('foto_kegiatan/' . $workreport->foto_kegiatan);
        //     }
        // } else {
        //     $filename = $workreport->foto_kegiatan;
        // }

        if ($request->status == 'continue' && empty($request->continue_note)) {
            return back()->withErrors([
                'continue_note' => 'Keterangan wajib diisi jika status Continue'
            ])->withInput();
        }

        DB::transaction(function () use ($request, $workreport) {

            $firstMember = $request->members[0];

            // 1. update data utama
            // $workreport->update($request->except('photos', 'nik', 'jam_mulai', 'jam_berakhir'));
            $workreport->update([

                ...$request->except('members', 'photos'),

                'nama' => $firstMember['nama'],

                'nik' => $firstMember['nik'],

                'jabatan' => $firstMember['jabatan'],

            ]);

            $workreport->members()->delete();

            foreach ($request->members as $member) {

                $workreport->members()->create([

                    'nama' => $member['nama'],

                    'nik' => $member['nik'],

                    'jabatan' => $member['jabatan'],

                ]);
            }
        });


        // 2. jika upload foto baru → replace semua
        if ($request->hasFile('photos')) {

            // 🔥 upload dulu (aman)
            $newFiles = [];

            foreach ($request->file('photos') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('foto_kegiatan', $file, $filename);
                $newFiles[] = $filename;
            }

            // // 🔥 hapus foto lama (DB + storage)
            // foreach ($workreport->photos as $oldPhoto) {
            //     Storage::disk('public')->delete('foto_kegiatan/' . $oldPhoto->file_path);
            //     $oldPhoto->delete();
            // }

            // 🔥 simpan foto baru ke DB
            foreach ($newFiles as $filename) {
                $workreport->photos()->create([
                    'file_path' => $filename
                ]);
            }
        }



        // $newRequest = $request->except('nik', 'jam_mulai', 'jam_berakhir');
        // $newRequest['foto_kegiatan'] = $filename;

        // $workreport->update($newRequest);

        return redirect()->route('workreport.index')->with('success', 'Data Laporan Pekerjaan Berhasil Diupdate.');
    }

    public function destroy(String $id)
    {
        // WorkReport::destroy($id);

        // $workreport = WorkReport::find($id);
        // if ($workreport->foto_kegiatan != null) {
        //     Storage::disk('public')->delete('foto_kegiatan/' . $workreport->foto_kegiatan);
        // }

        // $workreport->delete();

        $workreport = WorkReport::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && $workreport->user_id != $user->id) {
            abort(403);
        }

        // 1. hapus semua file foto dari storage
        foreach ($workreport->photos as $photo) {
            Storage::disk('public')->delete('foto_kegiatan/' . $photo->file_path);
        }

        // 2. hapus workreport (photos ikut kehapus karena cascade)
        $workreport->delete();


        return redirect()->route('workreport.index')->with('success', 'Data Laporan Pekerjaan Berhasil Dihapus.');
    }

    public function deletePhoto($id)
    {
        $photo = \App\Models\WorkReportPhoto::findOrFail($id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (
            !$user->isAdmin() &&
            $photo->workReport->user_id != $user->id
        ) {
            abort(403);
        }

        // hapus file dari storage
        Storage::disk('public')->delete('foto_kegiatan/' . $photo->file_path);

        // hapus dari database
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus');
    }

    // public function show($id)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if ($user->isMekanik()) {
    //         $workreport = WorkReport::with('photos')
    //             ->where('user_id', $user->id)
    //             ->findOrFail($id);
    //     } else {
    //         $workreport = WorkReport::with('photos')->findOrFail($id);
    //     }

    //     return view('workreport.show', compact('workreport'));
    // }

    public function show($id)
    {
        $workreport = WorkReport::with([
            'photos',
            'members',
            'reviewer',
            'approver',
        ])->findOrFail($id);

        return view('workreport.show', compact('workreport'));
    }

    public function downloadPdf($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isForeman()) {
            abort(403);
        }

        $workreport = WorkReport::with([
            'photos',
            'members'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'workreport.pdf',
            compact('workreport')
        );

        $pdf->setPaper('A4', 'portrait');

        $filename =
            'work_report_' .
            $workreport->nama . '_' .
            now()->format('d-m-Y_H-i-s') .
            '.pdf';

        return $pdf->download($filename);
    }

    public function approveByForeman(
        WorkReport $workReport,
        ApprovalService $approvalService
    ) {
        try {

            $approvalService->approveByForeman(
                $workReport,
                Auth::user()
            );

            return redirect()
                ->route('workreport.show', $workReport)
                ->with(
                    'success',
                    'Work Report berhasil direview dan dikirim ke tahap approval.'
                );
        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function rejectByForeman(
        Request $request,
        WorkReport $workReport,
        ApprovalService $approvalService
    ) {
        $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        try {

            $approvalService->rejectByForeman(
                $workReport,
                Auth::user(),
                $request->rejection_reason
            );

            return redirect()
                ->route('workreport.show', $workReport)
                ->with(
                    'success',
                    'Work Report berhasil ditolak.'
                );
        } catch (\Throwable $e) {

            // dd($e);
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function approveFinal(
        WorkReport $workReport,
        ApprovalService $approvalService
    ) {
        try {

            $approvalService->approveFinal(
                $workReport,
                Auth::user()
            );

            return redirect()
                ->route('workreport.show', $workReport)
                ->with(
                    'success',
                    'Work Report berhasil di-approve.'
                );
        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function rejectFinal(
        Request $request,
        WorkReport $workReport,
        ApprovalService $approvalService
    ) {
        $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        try {

            $approvalService->rejectFinal(
                $workReport,
                Auth::user(),
                $request->rejection_reason
            );

            return redirect()
                ->route('workreport.show', $workReport)
                ->with(
                    'success',
                    'Work Report berhasil ditolak.'
                );
        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
