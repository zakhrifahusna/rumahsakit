<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Jadwalpoliklinik;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Exports\AntrianExport;
use App\Models\Datapasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Query untuk mengambil data jadwal poliklinik
        $jadwalpoliklinik = Jadwalpoliklinik::orderBy('created_at', 'desc')->get();
        $filter = "";
        
        // Ambil parameter dari permintaan GET
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');
        
        // Inisialisasi query untuk semua data antrian
        $query = Antrian::query();
        
        // Filter berdasarkan rentang tanggal jika parameter ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_berobat', [$start_date, $end_date]);
            $filter = ["start_date" => $start_date, 'end_date' => $end_date];
        }

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('kode_jadwalpoliklinik', 'like', "%$search%")
                    ->orWhere('kode_antrian', 'like', "%$search%")
                    ->orWhere('no_antrian', 'like', "%$search%")
                    ->orWhere('nama_pasien', 'like', "%$search%")
                    ->orWhere('no_telp', 'like', "%$search%")
                    ->orWhere('nama_dokter', 'like', "%$search%")
                    ->orWhere('poliklinik', 'like', "%$search%")
                    ->orWhere('penjamin', 'like', "%$search%")
                    ->orWhere('no_kbpjs', 'like', "%$search%");
            });
        }

        // Ambil data antrian sesuai dengan query yang dibuat
        $antrian = $query->orderBy('tanggal_berobat', 'asc')->get();
        
        return view('antrian.index', compact('antrian', 'filter'));
    }

    public function index2(Request $request)
    { 
        $userId = Auth::id();
        $datapasien = Datapasien::where('user_id', $userId)->first();

        if ($datapasien) {
            $antrian = Antrian::where('id_pasien', $datapasien->id)->get();
        } else {
            $antrian = [];
        }

        return view('antrian.index2', compact('antrian'));
    }

    public function generateAntrian($id){

        $antrian = Antrian::where('id',$id)->first();
        $pdf = PDF::loadView('pdf/antrian', ['antrian' => $antrian]);
        return $pdf->stream('Antrian.pdf');
    }

    public function generateLaporanAntrian(Request $request){

        // Ambil parameter dari permintaan GET
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search = $request->search; // Ambil parameter pencarian

        // Inisialisasi query untuk semua data antrian
        $query = Antrian::query();

        // Filter berdasarkan rentang tanggal jika parameter ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_berobat', [$start_date, $end_date]);
        }

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('kode_jadwalpoliklinik', 'like', "%$search%")
                    ->orWhere('kode_antrian', 'like', "%$search%")
                    ->orWhere('no_antrian', 'like', "%$search%")
                    ->orWhere('nama_pasien', 'like', "%$search%")
                    ->orWhere('no_telp', 'like', "%$search%")
                    ->orWhere('nama_dokter', 'like', "%$search%")
                    ->orWhere('poliklinik', 'like', "%$search%")
                    ->orWhere('penjamin', 'like', "%$search%")
                    ->orWhere('no_kbpjs', 'like', "%$search%");
            });
        }

        // Ambil data sesuai dengan filter yang diterapkan
        $antrian = $query->get();

        // Generate PDF
        $pdf = PDF::loadView('pdf/list_antrian', ['antrian' => $antrian])->setPaper('a4', 'landscape');
        return $pdf->stream('list_antrian.pdf');
    }


    public function generateLaporanExcel(Request $request)
    {
          // Ambil parameter dari permintaan GET
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');

        // Inisialisasi query untuk semua data antrian
        $query = Antrian::query();

        // Filter berdasarkan rentang tanggal jika parameter ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_berobat', [$start_date, $end_date]);
        }

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('kode_jadwalpoliklinik', 'like', "%$search%")
                    ->orWhere('kode_antrian', 'like', "%$search%")
                    ->orWhere('no_antrian', 'like', "%$search%")
                    ->orWhere('nama_pasien', 'like', "%$search%")
                    ->orWhere('no_telp', 'like', "%$search%")
                    ->orWhere('nama_dokter', 'like', "%$search%")
                    ->orWhere('poliklinik', 'like', "%$search%")
                    ->orWhere('penjamin', 'like', "%$search%")
                    ->orWhere('no_kbpjs', 'like', "%$search%");
            });
        }

        // Ambil data antrian sesuai dengan query yang dibuat
        $antrian = $query->orderBy('tanggal_berobat', 'asc')->get();

        // Buat ekspor dengan data yang telah difilter
        return Excel::download(new AntrianExport($antrian), 'list_antrian.xlsx');
    }

    public function generateLaporanPasien()
    {
        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();
        
        // Ambil data pasien berdasarkan user_id
        $datapasien = Datapasien::where('user_id', $userId)->first();

        // Jika data pasien tidak ditemukan, redirect dengan pesan error
        if (!$datapasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        // Ambil data antrian berdasarkan ID pasien
        $antrian = Antrian::where('id_pasien', $datapasien->id)
                        ->with(['jadwalpoliklinik.dokter', 'jadwalpoliklinik.poliklinik'])
                        ->get();

        // Generate PDF
        $pdf = PDF::loadView('pdf.pendaftaran_pasien', compact('antrian', 'datapasien'));

        // Atur nama file PDF dan unduh
        return $pdf->download('pendaftaran_pasien.pdf');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
