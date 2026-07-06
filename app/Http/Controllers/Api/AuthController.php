<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rider;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:customer,owner,rider',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('food_delivery_token')->plainTextToken;

        return response()->json([
            'user' => $this->formatUser($user),
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->status === 'suspended') {

    return response()->json([
        'message' => 'Account suspended'
    ], 403);

}

        $token = $user->createToken('food_delivery_token')->plainTextToken;

        return response()->json([
            'user' => $this->formatUser($user),
            'token' => $token
        ]);
    }

  public function user(Request $request)
{
    $user = $request->user()->load([
        'restaurant',
        'rider'
    ]);

    return response()->json(
        $this->formatUser($user)
    );
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // ✅ CENTRALIZED USER FORMAT (VERY IMPORTANT)
private function formatUser($user)
{
    return [
        'id' => $user->id,
        'firstname' => $user->firstname,
        'lastname' => $user->lastname,
        'email' => $user->email,
        'role' => $user->role,

        // OWNER
        'restaurant_exists' => $user->restaurant !== null,
        'restaurant' => $user->restaurant,

        // RIDER
        'rider_profile_exists' => $user->rider !== null,
        'rider' => $user->rider,
    ];
}
}