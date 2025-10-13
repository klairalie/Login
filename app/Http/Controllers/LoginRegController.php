<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use App\Models\Login;

class LoginRegController extends Controller
{
    public function showLogin()
    {
        return view('process.login');
    }

    public function login(Request $request)
    {
        // ✅ Validate inputs
        $validated = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // ✅ Find user
        $user = Login::where('email', $validated['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Account not found.'],
            ]);
        }

        // ✅ Verify password
        $passwordValid = Hash::check($validated['password'], $user->password)
            || $user->password === $validated['password']; // fallback for plain text

        if (!$passwordValid) {
            throw ValidationException::withMessages([
                'email' => ['Invalid password.'],
            ]);
        }

        // ✅ Store session data
        session([
            'user_id'       => $user->id,
            'user_email'    => $user->email ?? $user->username,
            'user_position' => $user->position,
            'logged_in_at'  => now(),
        ]);

        // ✅ Force the session to be saved before redirecting away
        $request->session()->save();

        // ✅ Create encrypted token
        $tokenPayload = [
            'email'     => $user->email ?? $user->username,
            'position'  => $user->position,
            'timestamp' => now()->timestamp,
        ];

        $encryptedToken = Crypt::encryptString(json_encode($tokenPayload));
        $token = urlencode($encryptedToken);

        // ✅ Redirect based on position
        switch (strtolower($user->position)) {
            case 'administrative manager':
                return redirect()->away("http://Capstone-Admin.test/auth/verify?token={$token}");
            case 'human resource manager':
                return redirect()->away("http://Humanresource.test/auth/verify?token={$token}");
            case 'finance manager':
                return redirect()->away("http://Finance.test/auth/verify?token={$token}");
            default:
                return back()->withErrors([
                    'email' => 'Your position is not authorized or unrecognized.',
                ]);
        }
    }
}
