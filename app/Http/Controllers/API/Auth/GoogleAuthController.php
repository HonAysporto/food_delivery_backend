<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Google callback.
     */
    public function callback()
    {
        try {

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {

                $names = explode(' ', $googleUser->name);

                $user = User::create([
                    'firstname' => $names[0] ?? '',
                    'lastname' => $names[1] ?? '',
                    'email' => $googleUser->email,
                    'password' => Hash::make(uniqid()),
                    'role' => 'customer',
                ]);
            }

            $token = $user
                ->createToken('food_delivery_token')
                ->plainTextToken;

            return redirect(
                "http://localhost:5173/google-auth?"
                . "token={$token}"
                . "&id={$user->id}"
            );

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
}