<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $informasi = Informasi::all();
        // Debugging untuk mencatat informasi ke dalam log
        foreach ($informasi as $item) {
            Log::info('Data Informasi:', [
                'id' => $item->id,
                'judul' => $item->judul,
                'foto_informasi' => $item->foto_informasi,
                'deskripsi' => $item->deskripsi,
                'URL Gambar' => asset('storage/foto_informasi/' . $item->foto_informasi),
            ]);
        }

        return view('informasi.index', compact('informasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info('Metode create dipanggil');
        return view('informasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'judul' => 'required|max:255',
            'foto_informasi' => 'image|nullable|max:1999',
            'deskripsi' => 'required',
        ]);

        // Proses upload file foto
        if ($request->hasFile('foto_informasi')) {
            $filenameWithExt = $request->file('foto_informasi')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto_informasi')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('foto_informasi')->storeAs('public/foto_informasi', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Simpan data informasi baru
        $informasi = new Informasi;
        $informasi->judul = $validatedData['judul'];
        $informasi->foto_informasi = $fileNameToStore;
        $informasi->deskripsi = $validatedData['deskripsi'];
        $informasi->save();

        // Redirect dengan pesan sukses
        return redirect()->route('informasi.index')->with('success', 'Informasi berhasil disimpan!');
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
        $item = Informasi::findOrFail($id);
        return view('informasi.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $informasi = Informasi::findOrFail($id);
        return view('informasi.update', compact('informasi'));
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
            'judul' => 'required|max:255',
            'foto_informasi' => 'image|nullable|max:1999',
            'deskripsi' => 'required',
        ]);

        // Temukan data informasi berdasarkan ID
        $informasi = Informasi::findOrFail($id);

        // Proses upload file foto jika ada
        if ($request->hasFile('foto_informasi')) {
            // Hapus foto lama jika ada
            if ($informasi->foto_informasi != 'noimage.jpg') {
                Storage::delete('public/foto_informasi/' . $informasi->foto_informasi);
            }

            // Proses simpan foto baru
            $filenameWithExt = $request->file('foto_informasi')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('foto_informasi')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('foto_informasi')->storeAs('public/foto_informasi', $fileNameToStore);

            // Simpan nama file baru ke dalam database
            $informasi->foto_informasi = $fileNameToStore;
        }

        // Perbarui data informasi
        $informasi->judul = $validatedData['judul'];
        $informasi->deskripsi = $validatedData['deskripsi'];
        $informasi->save();

        // Redirect dengan pesan sukses
        return redirect()->route('informasi.index')->with('success', 'Informasi berhasil diupdate!');
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
        $informasi = Informasi::findOrFail($id);
        Storage::delete('public/foto_informasi/' . $informasi->foto_informasi);
        $informasi->delete();
        
        return redirect()->route('informasi.index')->with('success', 'Informasi berhasil dihapus.');
    }
}
