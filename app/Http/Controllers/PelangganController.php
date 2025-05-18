<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use App\Models\Pelanggan;
use App\Models\User;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('be.home.index', [
            'title' => 'Home'
        ]);
    }

    public function profile()
    {
        $pelanggan = Pelanggan::where('email', Auth::guard('pelanggan')->user()->email)->first();
        
        return view('fe.profile', [
            'title' => 'Profile Pelanggan',
            'pelanggan' => $pelanggan,
        ]);
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        // Dapatkan data pelanggan terlebih dahulu
        $pelanggan = Pelanggan::where('email', Auth::guard('pelanggan')->user()->email)->first();

        // Validasi data
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email,'.$pelanggan->id,
            'no_hp' => 'required|string|max:20',
            'password' => 'required|min:6|max:12',
            'alamat1' => 'nullable|string|max:255',
            'kota1' => 'nullable|string|max:255',
            'propinsi1' => 'nullable|string|max:255',
            'kodepos1' => 'nullable|string|max:10',
            'alamat2' => 'nullable|string|max:255',
            'kota2' => 'nullable|string|max:255',
            'propinsi2' => 'nullable|string|max:255',
            'kodepos2' => 'nullable|string|max:10',
            'alamat3' => 'nullable|string|max:255',
            'kota3' => 'nullable|string|max:255',
            'propinsi3' => 'nullable|string|max:255',
            'kodepos3' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Mulai transaction
            DB::beginTransaction();

            $data = [
                'nama_pelanggan' => $request->nama_pelanggan,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat1' => $request->alamat1,
                'kota1' => $request->kota1,
                'propinsi1' => $request->propinsi1,
                'kodepos1' => $request->kodepos1,
                'alamat2' => $request->alamat2 ?? null,
                'kota2' => $request->kota2 ?? null,
                'propinsi2' => $request->propinsi2 ?? null,
                'kodepos2' => $request->kodepos2 ?? null,
                'alamat3' => $request->alamat3 ?? null,
                'kota3' => $request->kota3 ?? null,
                'propinsi3' => $request->propinsi3 ?? null,
                'kodepos3' => $request->kodepos3 ?? null,
            ];

            // Handle password update
            if ($request->password) {
                $data['password'] = Hash::make($request->password);
                // Update password user juga jika perlu
                Auth::user()->update(['password' => Hash::make($request->password)]);
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($pelanggan->foto && Storage::exists('public/'.$pelanggan->foto)) {
                    Storage::delete('public/'.$pelanggan->foto);
                }
                
                $foto = $request->file('foto');
                $filename = time().'_'.$foto->getClientOriginalName();
                $path = $foto->storeAs('public/profiles', $filename);
                $data['foto'] = 'profiles/'.$filename;
            }

            // Handle KTP upload
            if ($request->hasFile('url_ktp')) {
                // Hapus KTP lama jika ada
                if ($pelanggan->url_ktp && Storage::exists('public/'.$pelanggan->url_ktp)) {
                    Storage::delete('public/'.$pelanggan->url_ktp);
                }
                
                $ktp = $request->file('url_ktp');
                $filename = 'ktp_'.time().'_'.$ktp->getClientOriginalName();
                $path = $ktp->storeAs('public/ktp', $filename);
                $data['url_ktp'] = 'ktp/'.$filename;
            }

            // Update data pelanggan
            $pelanggan->update($data);

            // Update data user
            $user = User::find(Auth::id());
            $user->update([
                'name' => $request->nama_pelanggan,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
            ]);

            // Commit transaction
            DB::commit();

            return redirect('/home')->with('success', 'Profile berhasil diperbarui!');
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profile: '.$e->getMessage())
                ->withInput();
        }
    }

    public function signin()
    {
        return view('auth.signin');
    }

    public function signup()
    {
        return view('auth.signup');
    }      
    
    // User Registration
    public function signupUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|min:6|max:12',
        ]);

        try {
            DB::beginTransaction();

            $pelanggan = Pelanggan::create([
                'nama_pelanggan' => $request->name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat1' => '-',
                'kota1' => '-',
                'propinsi1' => '-',
                'kodepos1' => '-',
                'alamat2' => '-',
                'kota2' => '-',
                'propinsi2' => '-',
                'kodepos2' => '-',
                'alamat3' => '-',
                'kota3' => '-',
                'propinsi3' => '-',
                'kodepos3' => '-',
                'foto' => 'default.jpg',
                'url_ktp' => 'default.jpg',
            ]);

            DB::commit();

            Auth::guard('pelanggan')->login($pelanggan);
            $request->session()->put('loginId', $pelanggan->id);

            return redirect('/home')->with('success', 'Registrasi berhasil! Anda sudah login.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration error: '.$e->getMessage());
            return back()->with('fail', 'Terjadi kesalahan saat registrasi: '.$e->getMessage())->withInput();
        }
    }

    // Handle login request
    public function signinUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('pelanggan')->attempt($credentials)) {
            $request->session()->regenerate();
            $pelanggan = Auth::guard('pelanggan')->user();

            $request->session()->put('loginId', $pelanggan->id);
            return redirect('/home');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function signout(Request $request)
    {
        Auth::guard('pelanggan')->logout();

        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('Success', 'signout berhasil!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function store(Request $request)
    {
    
    }

    public function edit($id)
    {
    
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}