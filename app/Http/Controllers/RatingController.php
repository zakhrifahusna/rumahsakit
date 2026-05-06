<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Periksa apakah pengguna sudah memberikan penilaian untuk antrian ini
            $existingRating = Rating::where('antrian_id', $request->antrian_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
        // Jika sudah memberikan penilaian, kembalikan dengan pesan kesalahan
        return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk antrian ini.');
        }

        // Validasi input
        $request->validate([
        'dokter_id' => 'required|exists:dokter,id',
        'antrian_id' => 'required|exists:antrian,id',
        'rating' => 'required|integer|min:1|max:5',
        ]);

        // Buat penilaian baru
        Rating::create([
        'dokter_id' => $request->dokter_id,
        'antrian_id' => $request->antrian_id,
        'user_id' => auth()->id(),
        'rating' => $request->rating,
        ]);

        // Kembalikan dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih atas penilaiannya!');
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
