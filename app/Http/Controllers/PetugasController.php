<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Datapasien;
use App\Models\Dokter;
use App\Models\Jadwalpoliklinik;
use App\Models\Poliklinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Menghitung jumlah antrian per poliklinik
            $antrianData = DB::table('antrian')
            ->select('poliklinik', DB::raw('COUNT(*) as total'))
            ->groupBy('poliklinik')
            ->get();

        // Menghitung total poliklinik, dokter, user, pasien, jadwal poliklinik, dan antrian
        $poliklinik = Poliklinik::count();
        $dokter = Dokter::count();
        $datapasien = Datapasien::count();
        $jadwalpoliklinik = Jadwalpoliklinik::count();
        $antrian = Antrian::count();

        // Menyusun data yang akan dikirim ke view
        $data = [
            'content' => 'Selamat datang, anda login sebagai PETUGAS!',
            'poliklinik' => $poliklinik,
            'dokter' => $dokter,
            'datapasien' => $datapasien,
            'jadwalpoliklinik'=> $jadwalpoliklinik,
            'antrianData' => $antrianData,
        ];

        return view('dashboard-petugas', $data);
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
