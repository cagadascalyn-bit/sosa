<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'gender'  => 'nullable|in:Male,Female,Other',
            'phone'   => 'nullable|string|max:20',
            // jpeg/png/jpg only, max 2MB
            'picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->only('name', 'email', 'address', 'gender', 'phone');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('picture')) {
            $file     = $request->file('picture');
            $mime     = $file->getMimeType();          // e.g. image/jpeg
            $binary   = file_get_contents($file->getRealPath());
            $base64   = base64_encode($binary);

            // Store as a complete data URI — works anywhere, no filesystem needed
            $data['profile_picture_base64'] = "data:{$mime};base64,{$base64}";

            // Keep the old file-based column null so views prefer base64
            $data['profile_picture'] = null;
        }

        $user->update($data);
        return redirect()->route('profile.show')->with('toast_success', 'Profile updated successfully!');
    }
}
