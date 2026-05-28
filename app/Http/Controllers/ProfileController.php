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
            'picture' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'address', 'gender', 'phone');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('picture')) {
            $data['profile_picture'] = $request->file('picture')->store('profiles', 'public');
        }

        $user->update($data);
        return redirect()->route('profile.show')->with('toast_success', 'Profile updated successfully!');
    }
}
