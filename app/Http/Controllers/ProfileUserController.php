<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('be.profile.index', [
            'title' => 'User Profile',
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:20|unique:users,no_hp,' . $user->id,
            'password' => 'nullable|string|min:6|max:12|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
            ];

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
            
                $path = $request->file('foto')->store('foto_users', 'public');
                $updateData['foto'] = $path;
            }

            $user->update($updateData);

            return redirect()->route('be.profile')->with([
                'swal' => [
                    'icon' => 'success',
                    'title' => 'Success!',
                    'text' => 'Profile updated successfully.',
                    'timer' => 1500
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'swal' => [
                    'icon' => 'error',
                    'title' => 'Failed!',
                    'text' => 'Failed to update profile: ' . $e->getMessage(),
                    'timer' => 3000
                ]
            ])->withInput();
        }
    }
}