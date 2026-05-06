<?php

namespace App\Http\Controllers;

use App\Exports\JadwalPoliklinikExport;
use App\Models\Dokter;
use App\Models\Jadwalpoliklinik;
use App\Models\Poliklinik;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class JadwalpoliklinikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');

        // Query dasar
        $query = Jadwalpoliklinik::query();

        // Filter berdasarkan tanggal jika ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_praktek', [$start_date, $end_date]);
        }

        // Filter berdasarkan pencarian nama dokter atau nama poliklinik jika ada
        if ($search) {
            $query->whereHas('dokter', function ($q) use ($search) {
                $q->where('nama_dokter', 'like', '%' . $search . '%');
            })->orWhereHas('dokter.poliklinik', function ($q) use ($search) {
                $q->where('nama_poliklinik', 'like', '%' . $search . '%');
            });
        }

        // Dapatkan hasil query
        $jadwalpoliklinik = $query->orderBy('tanggal_praktek', 'asc')->get();

        // Tampilkan halaman index dengan data yang sudah difilter
        return view('jadwalpoliklinik.index', compact('jadwalpoliklinik'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $poliklinik = Poliklinik::all();
        return view('jadwalpoliklinik.create', compact('poliklinik'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'tanggal_praktek' => 'required|array',
            'tanggal_praktek.*' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah' => 'required|integer|min:1',
        ]);
    
        foreach ($request->tanggal_praktek as $tanggal) {
            $jadwalpoliklinik = new Jadwalpoliklinik();
            $jadwalpoliklinik->dokter_id = $request->dokter_id;
            $jadwalpoliklinik->poliklinik_id = Dokter::find($request->dokter_id)->poliklinik_id;
            $jadwalpoliklinik->tanggal_praktek = $tanggal;
            $jadwalpoliklinik->jam_mulai = $request->jam_mulai;
            $jadwalpoliklinik->jam_selesai = $request->jam_selesai;
            $jadwalpoliklinik->jumlah = $request->jumlah;
    
            // Berikan nilai sementara untuk kode_jadwalpoliklinik
            $jadwalpoliklinik->kode_jadwalpoliklinik = 'TEMP';
    
            // Simpan data untuk mendapatkan ID
            $jadwalpoliklinik->save();
    
            // Buat kode jadwal setelah mendapatkan ID
            $dokter = Dokter::find($request->dokter_id);
            $jadwalpoliklinik->kode_jadwalpoliklinik = $dokter->id . $dokter->poliklinik_id . $jadwalpoliklinik->id;
    
            // Simpan kembali untuk update kode_jadwalpoliklinik
            $jadwalpoliklinik->save();
        }
    
        return redirect()->route('jadwalpoliklinik.index')->with('success', 'Data berhasil disimpan!');
        return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data. Silakan coba lagi.']);
    }

    public function getDokterByPoliklinik($poliklinik_id)
    {
        $dokter = Dokter::where('poliklinik_id', $poliklinik_id)->pluck('nama_dokter', 'id');
        return response()->json($dokter);
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
        $jadwalpoliklinik = Jadwalpoliklinik::findOrFail($id);
        $dokter = Dokter::all();
        return view('jadwalpoliklinik.update', compact('jadwalpoliklinik', 'dokter'));
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
            'dokter_id' => 'required|exists:dokter,id',
            'tanggal_praktek' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah' => 'required|integer|min:1',
        ]);


        $jadwalpoliklinik = Jadwalpoliklinik::findOrFail($id);
        $jadwalpoliklinik->dokter_id = $request->dokter_id;
        $jadwalpoliklinik->poliklinik_id = Dokter::find($request->dokter_id)->poliklinik_id;
        $jadwalpoliklinik->tanggal_praktek = $request->tanggal_praktek;
        $jadwalpoliklinik->jam_mulai = $request->jam_mulai;
        $jadwalpoliklinik->jam_selesai = $request->jam_selesai;
        $jadwalpoliklinik->jumlah = $request->jumlah;
        $jadwalpoliklinik->save();

        return redirect()->route('jadwalpoliklinik.index')->with('success', 'Data berhasil diperbarui!');
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
        $jadwalpoliklinik = Jadwalpoliklinik::findOrFail($id);
        $jadwalpoliklinik->delete();

        return redirect()->route('jadwalpoliklinik.index')->with('success', 'Data berhasil dihapus!');
    }

    public function generateLaporanJadwalPoliklinik(Request $request)
    {
        // Ambil parameter dari permintaan GET
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');

        // Inisialisasi query untuk semua data jadwal poliklinik
        $query = Jadwalpoliklinik::query();

        // Filter berdasarkan rentang tanggal jika parameter ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_praktek', [$start_date, $end_date]);
        }

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('kode_jadwalpoliklinik', 'like', "%$search%")
                    ->orWhereHas('dokter', function($q) use ($search) {
                        $q->where('nama_dokter', 'like', "%$search%");
                    })
                    ->orWhereHas('dokter.poliklinik', function($q) use ($search) {
                        $q->where('nama_poliklinik', 'like', "%$search%");
                    });
            });
        }

        // Ambil data sesuai dengan filter yang diterapkan
        $jadwalpoliklinik = $query->get();

        // Generate PDF
        $pdf = PDF::loadView('pdf.jadwalpoliklinik', ['jadwalpoliklinik' => $jadwalpoliklinik])
                ->setPaper('a4', 'landscape');

        // Return PDF untuk di-download atau di-stream
        return $pdf->stream('JadwalPoliklinik.pdf');
    }

    public function generateLaporanExcel(Request $request)
    {
        // Ambil parameter dari permintaan GET
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search = $request->search;

        // Inisialisasi query untuk semua data jadwal poliklinik
        $query = Jadwalpoliklinik::query();

        // Filter berdasarkan rentang tanggal jika parameter ada
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_praktek', [$start_date, $end_date]);
        }

        // Filter berdasarkan parameter pencarian jika ada
        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('kode_jadwalpoliklinik', 'like', "%$search%")
                      ->orWhereHas('dokter', function($q) use ($search) {
                          $q->where('nama_dokter', 'like', "%$search%");
                      })
                      ->orWhereHas('dokter.poliklinik', function($q) use ($search) {
                          $q->where('nama_poliklinik', 'like', "%$search%");
                      });
            });
        }

        // Ambil data sesuai dengan filter yang diterapkan
        $jadwalpoliklinik = $query->get();

        // Buat ekspor dengan data yang telah difilter
        return Excel::download(new JadwalPoliklinikExport($jadwalpoliklinik), 'jadwal_poliklinik.xlsx');
    }


}
