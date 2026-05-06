<?php

namespace App\Http\Controllers;

use App\Models\Datapasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PasienExport;

class DatapasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Ambil input pencarian
        $search = $request->input('search');

        // Query dasar untuk mengambil data pasien yang memiliki role 'pasien'
        $query = Datapasien::whereHas('user', function($query) {
            $query->where('roles', 'pasien');
        });

        // Jika ada pencarian, filter data berdasarkan semua kolom yang disebutkan
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('nama_pasien', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_telp', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('tempat_lahir', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_lahir', 'like', '%' . $search . '%')
                    ->orWhere('jenis_kelamin', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%')
                    ->orWhere('no_kberobat', 'like', '%' . $search . '%')
                    ->orWhere('no_kbpjs', 'like', '%' . $search . '%');
            });
        }

        // Dapatkan hasil query
        $dataPasien = $query->get();

        // Tampilkan view dengan data pasien yang difilter
        return view('pasien.index', compact('dataPasien'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        // Define the roles that have the same access rights
        $allowedRoles = ['admin', 'petugas', 'kepala_rs'];

        if (in_array($user->roles, $allowedRoles)) {
            // Jika user memiliki salah satu dari role yang diizinkan, tampilkan data pasien berdasarkan ID yang diberikan
            $dataPasien = Datapasien::findOrFail($id);
        } else {
            // Jika bukan salah satu dari role yang diizinkan, tampilkan data pasien milik pengguna yang login
            $dataPasien = Datapasien::where('user_id', $user->id)->first();

            if (!$dataPasien) {
                $dataPasien = new Datapasien([
                    'nama_pasien' => $user->nama_user,
                    'email' => $user->username,
                    'no_telp' => $user->no_telepon,
                    'user_id' => $user->id,
                ]);
                $dataPasien->save();
            } else {
                // Cek apakah file gambar ada di direktori yang sesuai
                $dataPasien->scan_ktp = $dataPasien->scan_ktp && file_exists(public_path('storage/' . $dataPasien->scan_ktp)) ? $dataPasien->scan_ktp : null;
                $dataPasien->scan_kberobat = $dataPasien->scan_kberobat && file_exists(public_path('storage/' . $dataPasien->scan_kberobat)) ? $dataPasien->scan_kberobat : null;
                $dataPasien->scan_kbpjs = $dataPasien->scan_kbpjs && file_exists(public_path('storage/' . $dataPasien->scan_kbpjs)) ? $dataPasien->scan_kbpjs : null;
                $dataPasien->scan_kasuransi = $dataPasien->scan_kasuransi && file_exists(public_path('storage/' . $dataPasien->scan_kasuransi)) ? $dataPasien->scan_kasuransi : null;
            }
        }

        return view('pasien.show', compact('dataPasien', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataPasien = Datapasien::find($id);
        return view('pasien.update', compact('dataPasien'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'alamat' => 'required|string',
            'no_kberobat' => 'nullable|string',
            'no_kbpjs' => 'nullable|string',
            'scan_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'scan_kberobat' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'scan_kbpjs' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'scan_kasuransi' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);
    
        $dataPasien = Datapasien::find($id);
        $dataPasien->update($request->except(['scan_ktp', 'scan_kberobat', 'scan_kbpjs', 'scan_kasuransi']));
    
        if ($request->hasFile('scan_ktp')) {
            $path = $request->file('scan_ktp')->store('scan_ktp', 'public');
            $dataPasien->update(['scan_ktp' => $path]);
        }
    
        if ($request->hasFile('scan_kberobat')) {
            $path = $request->file('scan_kberobat')->store('scan_kberobat', 'public');
            $dataPasien->update(['scan_kberobat' => $path]);
        }
    
        if ($request->hasFile('scan_kbpjs')) {
            $path = $request->file('scan_kbpjs')->store('scan_kbpjs', 'public');
            $dataPasien->update(['scan_kbpjs' => $path]);
        }
    
        if ($request->hasFile('scan_kasuransi')) {
            $path = $request->file('scan_kasuransi')->store('scan_kasuransi', 'public');
            $dataPasien->update(['scan_kasuransi' => $path]);
        }
    
        return redirect()->route('pasien.show', $dataPasien->id)->with('success', 'Data pasien berhasil diupdate.');
        return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui data. Silakan coba lagi.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataPasien = DataPasien::findOrFail($id);
        $dataPasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus');
    }

    public function generateLaporandatapasien(Request $request)
    {
        // Ambil parameter pencarian dari permintaan GET
        $search = $request->input('search');

        // Inisialisasi query untuk semua data pasien
        $query = DataPasien::whereHas('user', function($query) {
            $query->where('roles', 'pasien');
        });

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('nama_pasien', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('no_telp', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('tempat_lahir', 'like', "%$search%")
                    ->orWhere('tanggal_lahir', 'like', "%$search%")
                    ->orWhere('jenis_kelamin', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('no_kberobat', 'like', "%$search%")
                    ->orWhere('no_kbpjs', 'like', "%$search%");
            });
        }

        // Ambil data sesuai dengan filter yang diterapkan
        $dataPasien = $query->get();

        // Generate PDF
        $pdf = PDF::loadView('pdf.data_pasien', ['dataPasien' => $dataPasien])
          ->setPaper('a4', 'landscape');

        // Return PDF untuk di-download atau di-stream
        return $pdf->stream('LaporanDataPasien.pdf');
    }

    public function generateLaporanExcel(Request $request)
    {
        // Ambil parameter pencarian dari permintaan GET
        $search = $request->input('search');

        // Inisialisasi query untuk semua data pasien
        $query = DataPasien::whereHas('user', function($query) {
            $query->where('roles', 'pasien');
        });

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('nama_pasien', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('no_telp', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('tempat_lahir', 'like', "%$search%")
                    ->orWhere('tanggal_lahir', 'like', "%$search%")
                    ->orWhere('jenis_kelamin', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('no_kberobat', 'like', "%$search%")
                    ->orWhere('no_kbpjs', 'like', "%$search%");
            });
        }

        // Ambil data sesuai dengan filter yang diterapkan
        $dataPasien = $query->get();

        // Buat ekspor dengan data yang telah difilter
        return Excel::download(new PasienExport($dataPasien), 'LaporanDataPasien.xlsx');
    }


}
