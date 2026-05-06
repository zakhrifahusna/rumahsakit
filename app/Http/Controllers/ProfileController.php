<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login
        return view('user.profile', compact('user'));
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
        // Validasi input
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'no_telepon' => 'required|string|max:15',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Temukan user berdasarkan ID
        $user = User::findOrFail($id);

        // Mengatur penyimpanan file foto_user jika ada
        if ($request->hasFile('foto_user')) {
            // Hapus foto lama jika ada
            if ($user->foto_user) {
                Storage::disk('public')->delete('foto_user/' . $user->foto_user);
            }

            // Simpan foto baru
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->storeAs('foto_user', $fileName, 'public');
        } else {
            // Tetap menggunakan foto lama jika tidak ada foto baru
            $fileName = $user->foto_user;
        }

        // Update data pengguna
        $user->update([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password, 
            'no_telepon' => $request->no_telepon,
            'foto_user' => $fileName,
        ]);

        // Redirect ke dashboard berdasarkan roles
        return redirect()->route('dashboard-' . $user->roles)->with('success', 'Data berhasil diperbaharui!');
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
        //
    }
}
