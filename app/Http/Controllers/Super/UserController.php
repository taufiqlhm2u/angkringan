<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.super.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.super.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'no_telp' => 'required|regex:/^[0-9]+$/',
            'password' => 'required|string|min:8|confirmed',
            "password_confirmation" => 'required',
            'address' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',

            'role.required' => 'Role pengguna harus dipilih.',

            'no_telp.required' => 'No telepon wajib di isi.',
            'no_telp.regex' => 'Pastikan menggunkan format yang benar! (08xxx).',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',

            'address.required' => 'Alamat wajib di isi'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => 'active',
                'phone_number' => $request->no_telp,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('super.users.index')->with('sukses', 'Pengguna berhasil ditambahkan');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::find($id)->delete();
            return redirect()->route('super.users.index')->with('sukses', 'User Berhasil dihapus');
        } catch (Exception) {
            return back()->with('error', 'Gagal menghapus user');
        }
    }
}
