<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poliklinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil semua dokter dengan relasi ratings dan menghitung antrian
        $dokter = Dokter::with(['ratings', 'antrians'])->withCount('antrians')->latest()->get();

        return view('dokter.index', [
            'dokter' => $dokter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('Metode create dipanggil');
        $poliklinik = Poliklinik::all();
        return view('dokter.create', compact('poliklinik'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_dokter' => 'required|max:255',
            'poliklinik_id' => 'required',
            'foto_dokter' => 'image|nullable|max:1999',
            'hari_praktek' => 'required|array', // Validasi hari_praktek sebagai array
        ]);

        // Proses upload file foto
        if ($request->hasFile('foto_dokter')) {
            $filenameWithExt = $request->file('foto_dokter')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto_dokter')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('foto_dokter')->storeAs('public/foto_dokter', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Simpan data dokter baru
        $dokter = new Dokter;
        $dokter->nama_dokter = $validatedData['nama_dokter'];
        $dokter->poliklinik_id = $validatedData['poliklinik_id'];
        $dokter->foto_dokter = $fileNameToStore;
        $dokter->hari_praktek = json_encode($request->hari_praktek); // Simpan hari_praktek dalam format JSON
        $dokter->save();

        return redirect()->route('dokter.index')->with('success', 'Data berhasil disimpan!');
        return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data. Silakan coba lagi.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dokter = Dokter::find($id);
        $dokter = Dokter::with('ratings')->findOrFail($id);
        $averageRating = round($dokter->ratings->avg('rating'), 2);
        return view('dokter.show', compact('dokter', 'averageRating'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dokter = Dokter::find($id);
        $poliklinik = Poliklinik::all();
        return view('dokter.update', compact('dokter', 'poliklinik'));
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
        // Validasi data
        $validatedData = $request->validate([
            'nama_dokter' => 'required|max:255',
            'poliklinik_id' => 'required',
            'foto_dokter' => 'image|nullable|max:1999',
            'hari_praktek' => 'required|array', 
            'hari_praktek.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu', 
        ]);

        // Cari dokter berdasarkan ID
        $dokter = Dokter::findOrFail($id);

        // Jika ada file foto baru yang diupload
        if ($request->hasFile('foto_dokter')) {
            $filenameWithExt = $request->file('foto_dokter')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto_dokter')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('foto_dokter')->storeAs('public/foto_dokter', $fileNameToStore);
            
            // Hapus foto lama jika ada
            if ($dokter->foto_dokter != 'noimage.jpg') {
                Storage::delete('public/foto_dokter/' . $dokter->foto_dokter);
            }
            $dokter->foto_dokter = $fileNameToStore;
        }

        // Update data dokter
        $dokter->nama_dokter = $validatedData['nama_dokter'];
        $dokter->poliklinik_id = $validatedData['poliklinik_id'];
        $dokter->hari_praktek = json_encode($request->hari_praktek);
        $dokter->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('dokter.index')->with('success', 'Data berhasil diperbarui!');
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
        $dokter = Dokter::findOrFail($id);

        // Hapus file foto jika bukan 'noimage.jpg'
        if ($dokter->foto_dokter != 'noimage.jpg') {
            Storage::delete('public/foto_dokter/' . $dokter->foto_dokter);
        }

        // Hapus data dokter dari database
        $dokter->delete();

        return redirect()->route('dokter.index')->with('success', 'Data berhasil dihapus!');
    }

    
}
