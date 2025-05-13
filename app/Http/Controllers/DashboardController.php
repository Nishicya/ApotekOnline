<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Check if session exists
         if(!session()->has('loginId')) {
            return redirect()->route('login')->with('fail', 'Please login first!');
        }

        // Get user data
        $user = User::find(session('loginId'));
        
        // Check if user exists
        if(!$user) {
            return redirect()->route('login')->with('fail', 'User not found!');
        }

        switch ($user->role) {
            case 'admin':
                return view('be.admin.index', [
                    'title' => 'Dashboard Admin'
                ]);
            case 'apoteker':
                return view('be.apoteker.index', [
                    'title' => 'Dashboard Apoteker'
                ]);
            case 'pemilik':
                return view('be.pemilik.index', [
                    'title' => 'Dashboard Pemilik'
                ]);
            case 'karyawan':
                return view('be.karyawan.index', [
                    'title' => 'Dashboard karyawan'
                ]);
            case 'kasir':
                return view('be.kasir.index', [
                    'title' => 'Dashboard kasir'
                ]);
            default:
                return redirect()->route('home');
        }
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
