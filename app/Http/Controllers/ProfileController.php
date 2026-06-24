<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // GET PROFILE
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
        ]);
    }

    // UPDATE PROFILE INFO
    public function update(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = $request->user();
        $user->update($request->only('firstname', 'lastname', 'email'));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    // CHANGE PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }
}