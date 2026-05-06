<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Datapasien;
use App\Models\Jadwalpoliklinik;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now()->setTimezone('Asia/Jakarta');

        // Ambil jadwal untuk hari ini yang belum lewat waktu sekarang
        $jadwalHariIni = Jadwalpoliklinik::with('dokter.ratings')
                                ->whereDate('tanggal_praktek', $today)
                                ->where('jam_selesai', '>', $now->format('H:i'))
                                ->get();

        $tomorrow = Carbon::tomorrow();
        $jadwalBesok = JadwalPoliklinik::with('dokter.ratings')
                                ->whereDate('tanggal_praktek', $tomorrow)
                                ->get();

        return view('pendaftaran.index', compact('today', 'tomorrow', 'jadwalHariIni', 'jadwalBesok'));
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
        $user = Auth::user();

        if ($user->roles == 'admin' || $user->roles == 'petugas') {
            $request->validate([
                'nama_pasien' => 'required|string|max:255',
                'penjamin' => 'required',
                'no_telp' => 'nullable|string|max:15',
            ]);

            $nama_pasien = $request->nama_pasien;
            $id_pasien = null;
            $no_telp = $request->no_telp;
        } else {
            $datapasien = Datapasien::where('user_id', $user->id)->first();

            // Periksa apakah data pasien sudah lengkap
            $requiredFields = [
                'nik', 'nama_pasien', 'email', 'no_telp', 
                'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
                'alamat', 'scan_ktp', 'no_kberobat', 'scan_kberobat'
            ];

            foreach ($requiredFields as $field) {
                if (empty($datapasien->$field)) {
                    return back()->withErrors(['msg' => 'Data diri pasien belum lengkap. Harap lengkapi semua data sebelum mendaftar.']);
                }
            }

            $request->validate([
                'penjamin' => 'required',
                'scan_surat_rujukan' => 'required_if:penjamin,BPJS|file|mimes:jpeg,png,pdf',
            ]);

            if ($request->penjamin === 'BPJS' && (empty($datapasien->no_kbpjs) || empty($datapasien->scan_kbpjs))) {
                return back()->withErrors(['msg' => 'Data BPJS belum lengkap, harap lengkapi data BPJS terlebih dahulu!']);
            }

            if ($request->penjamin === 'Asuransi' && empty($datapasien->scan_kasuransi)) {
                return back()->withErrors(['msg' => 'Data Asuransi belum lengkap, harap lengkapi data Asuransi terlebih dahulu!']);
            }

            $nama_pasien = $datapasien->nama_pasien;
            $id_pasien = $datapasien->id;
            $no_telp = $datapasien->no_telp; 
        }

        $pendaftaran = new Pendaftaran();
        $pendaftaran->jadwalpoliklinik_id = $request->jadwalpoliklinik_id;
        $pendaftaran->penjamin = $request->penjamin;
        $pendaftaran->nama_pasien = $nama_pasien;
        $pendaftaran->id_pasien = $id_pasien;

        if ($user->roles != 'admin' && $user->roles != 'petugas' && $request->hasFile('scan_surat_rujukan')) {
            $file = $request->file('scan_surat_rujukan');
            $path = $file->store('public/surat_rujukan');
            $pendaftaran->scan_surat_rujukan = $path;
        }

        $jadwalpoliklinik = Jadwalpoliklinik::findOrFail($request->jadwalpoliklinik_id);
        if ($jadwalpoliklinik->jumlah <= 0) {
            return back()->withErrors(['msg' => 'Kuota pendaftaran habis']);
        }
        $jadwalpoliklinik->decrement('jumlah');

        $pendaftaran->save();

        // Generate nomor antrian berdasarkan jumlah antrian yang ada
        $no_antrian = Antrian::where('jadwalpoliklinik_id', $jadwalpoliklinik->id)->count() + 1;

        // Membuat kode antrian gabungan
        $kode_antrian = $jadwalpoliklinik->poliklinik_id . $jadwalpoliklinik->dokter_id . $jadwalpoliklinik->id . $pendaftaran->id . $user->id . $no_antrian;

        // Simpan data antrian
        $antrian = Antrian::create([
            'kode_antrian' => $kode_antrian,
            'kode_jadwalpoliklinik' => $jadwalpoliklinik->kode_jadwalpoliklinik,
            'no_antrian' => $no_antrian,
            'nama_pasien' => $nama_pasien,
            'no_telp' => $no_telp, 
            'jadwalpoliklinik_id' => $jadwalpoliklinik->id,
            'id_pasien' => $id_pasien,
            'nama_dokter' => $jadwalpoliklinik->dokter->nama_dokter,
            'poliklinik' => $jadwalpoliklinik->poliklinik->nama_poliklinik,
            'penjamin' => $request->penjamin,
            'no_kbpjs' => ($request->penjamin === 'BPJS' && isset($datapasien)) ? $datapasien->no_kbpjs : null,
            'scan_kbpjs' => ($request->penjamin === 'BPJS' && isset($datapasien)) ? $datapasien->scan_kbpjs : null,
            'scan_kasuransi' => ($request->penjamin === 'Asuransi' && isset($datapasien)) ? $datapasien->scan_kasuransi : null,
            'tanggal_berobat' => $jadwalpoliklinik->tanggal_praktek,
            'tanggal_reservasi' => now(),
            'user_id' => Auth::id(),
            'scan_surat_rujukan' => $request->hasFile('scan_surat_rujukan') ? $path : null,
        ]);

        // Kirim pesan WhatsApp jika dipilih 'yes'
        if ($no_telp && $request->kirim_whatsapp === 'yes') {
            $rand = $this->generateRandomString();
            $path = 'public/antrian/' . $rand . '.pdf';
            $pdf = PDF::loadView('pdf/antrian', ['antrian' => $antrian]);
            $content = $pdf->download()->getOriginalContent();
            Storage::put($path, $content);

            $message = "Yth. Bapak/Ibu $nama_pasien,\n\n";
            $message .= "Terima kasih telah mendaftar di RSUD Pasaman Barat. Berikut adalah kartu antrian Anda untuk kunjungan pada tanggal: " . $jadwalpoliklinik->tanggal_praktek->format('d/m/Y') . "\n\n";
            $message .= "Harap datang tepat waktu sesuai jadwal yang telah ditentukan. \n\n";
            $message .= "Hormat kami,\nRSUD Pasaman Barat";

            $get_file = base_path() . "/storage/app/" . $path;
            $token = "vib9-jk_C@@3T3n2fvf4";
            $cFile = curl_file_create($get_file,"antrian.pdf","Antrian.pdf");
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $no_telp, 
                    'message' => $message,
                    'filename' => 'Antrian.pdf',
                    'file' => $cFile,
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $token
                ),
            ));

            $response = curl_exec($curl);
            $curl_error = curl_error($curl);
            curl_close($curl);

            if ($curl_error) {
                return redirect()->route('pendaftaran.index')->with('error', 'Pendaftaran berhasil, tapi gagal mengirim ke WhatsApp. Error: ' . $curl_error);
            }

            if ($response) {
                return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil! Pesan terkirim ke WhatsApp.');
            } else {
                return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil, tapi gagal mengirim ke WhatsApp. Response: ' . $response);
            }
        }

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil!');
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

    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
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
