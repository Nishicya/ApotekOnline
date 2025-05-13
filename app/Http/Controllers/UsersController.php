<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::All();

        return view('be.user.index', [
            'title' => 'User Management',
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('be.user.create', [
            'title' => 'User Management Create',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:12',
            'role' => 'required|in:admin,pemilik,karyawan,apoteker,kasir',
            'no_hp' => 'required',
        ]);


        // Buat user dengan role hasil mapping
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('user.manage')->with('success', 'User created successfully.');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('be.user.edit', [
            'title' => 'User Management Edit',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'no_hp' => 'required', 
            'role' => 'required|in:admin,pemilik,karyawan,apoteker,kasir',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ];

        $user->update($data);

        return redirect()->route('user.manage')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('user.manage')->with('success', 'User deleted successfully.');
    }

}