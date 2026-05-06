<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatauserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return view('user.index', compact('user')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username',
            'password' => 'required|string|min:6|confirmed', 
            'no_telepon' => 'required|string|max:15',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'roles' => 'required|in:admin,kepala_rs,petugas,pasien',
        ]);

        // Mengatur penyimpanan file foto_user jika ada
        $fileName = null;
        if ($request->hasFile('foto_user')) {
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->storeAs('foto_user', $fileName, 'public');
        }

        // Menyimpan data user ke database
        User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'foto_user' => $fileName,
            'roles' => $request->roles,
        ]);

        // Redirect ke halaman index user dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'Data berhasil disimpan!');
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
        $user = User::findOrFail($id);
        return view('user.update', compact('user'));
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
            'roles' => 'required|in:admin,kepala_rs,petugas,pasien',
        ]);

        $user = User::findOrFail($id);

        // Mengatur penyimpanan file foto_user jika ada
        if ($request->hasFile('foto_user')) {
            // Hapus foto lama jika ada
            if ($user->foto_user) {
                Storage::disk('public')->delete('foto_user/' . $user->foto_user);
            }
            
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->storeAs('foto_user', $fileName, 'public');
        } else {
            $fileName = $user->foto_user; // Tetap menggunakan foto lama jika tidak ada foto baru
        }

        // Update data pengguna
        $user->update([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Update password jika diisi
            'no_telepon' => $request->no_telepon,
            'foto_user' => $fileName,
            'roles' => $request->roles,
        ]);

        return redirect()->route('user.index')->with('success', 'Data berhasil diperbarui!');
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
        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->foto_user) {
            Storage::disk('public')->delete('foto_user/' . $user->foto_user);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Data berhasil dihapus!');
    }
}
