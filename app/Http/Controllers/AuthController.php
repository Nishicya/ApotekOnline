<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
     {
          return view('auth.login');
     }
     public function register()
     {
          return view('auth.register');
     }              

    // User Registration
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:12',
            'role' => 'required|in:admin,pemilik,karyawan,apoteker,kasir,kurir',
            'no_hp' => 'required',
        ]);

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return back()->with('gagal', 'Email sudah terdaftar.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->no_hp = $request->no_hp;
        $user->role = $request->role;

        if ($user->save()) {
            Auth::login($user);
            $request->session()->put('loginId', $user->id);
            switch (Auth::user()->role) {
            case 'admin':
                return redirect()->intended('/admin')->with('Success', 'Registrasi berhasil!');
            case 'pemilik':
                return redirect()->intended('/pemilik')->with('Success', 'Registrasi berhasil!');
            case 'karyawan':
                return redirect()->intended('/karyawan')->with('Success', 'Registrasi berhasil!');
            case 'apoteker':
                return redirect()->intended('/apoteker')->with('Success', 'Registrasi berhasil!');
            case 'kasir':
                return redirect()->intended('/kasir')->with('Success', 'Registrasi berhasil!');
            case 'kurir':
                return redirect()->intended('/kurir')->with('Success', 'Registrasi berhasil!');
            }
        } else {
            return back()->withErrors(['email' => 'Email atau Password Salah.']);
            return back()->withErrors('password', 'Password minimal 6 karakter.');
        }
    }

    // Handle login request
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user(); // Retrieve the authenticated user
            $request->session()->put('loginId', $user->id);

            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin');
                case 'pemilik':
                    return redirect()->intended('/pemilik');
                case 'apoteker':
                    return redirect()->intended('/apoteker');
                case 'karyawan':
                    return redirect()->intended('/karyawan');
                case 'kasir':
                    return redirect()->intended('/kasir');
                case 'kurir':
                    return redirect()->intended('/kurir');
                default:
                    return redirect('/home');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('Success', 'Logout berhasil!');
    }
 

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}