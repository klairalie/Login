<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use App\Models\Login;
use Illuminate\Support\Facades\Mail;

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
            'otp'      => 'nullable|string',
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

        // ✅ OTP Phase 1: if no OTP yet, generate and email it
        if (empty($validated['otp'])) {
            $otp = rand(100000, 999999);

            session([
                'otp_code' => $otp,
                'otp_email' => $user->email,
                'otp_expires_at' => now()->addMinutes(5),
                'temp_email' => $validated['email'],
                'temp_password' => $validated['password'],
            ]);

            // ✅ Send OTP via mail (simple direct send, no queue)
            Mail::raw("Your OTP code is: {$otp}\nThis code expires in 5 minutes. from: 3RS Login System", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your OTP Code');

            });

            return back()->with([
                'otp_phase' => true,
                'status' => 'An OTP has been sent to your email. Please enter it below to continue.',
            ]);
        }

        // ✅ OTP Phase 2: verify OTP input
        $sessionOtp = session('otp_code');
        $expiresAt = session('otp_expires_at');
        $email = session('otp_email');

        if (
            !$sessionOtp ||
            now()->greaterThan($expiresAt) ||
            $validated['otp'] !== (string)$sessionOtp ||
            $validated['email'] !== $email
        ) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // ✅ Clear OTP data after success
        session()->forget(['otp_code', 'otp_email', 'otp_expires_at', 'temp_email', 'temp_password']);

        // ✅ Store session data
        session([
            'user_id'       => $user->id,
            'user_email'    => $user->email ?? $user->username,
            'user_position' => $user->position,
            'logged_in_at'  => now(),
        ]);

        session()->regenerate();
        session()->save();

        // ✅ Create encrypted token
        $tokenPayload = [
            'email'     => $user->email ?? $user->username,
            'position'  => $user->position,
            'timestamp' => now()->timestamp,
        ];

        $encryptedToken = Crypt::encryptString(json_encode($tokenPayload));
        $token = urlencode($encryptedToken);

        // ✅ Redirect based on position (unchanged)
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

     // ✅ Forgot Password Feature
    public function showForgotPassword()
    {
        return view('process.forgot-password');
    }


   public function sendResetOTP(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = Login::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Email not found.']);
    }

    // Generate OTP and expiration
    $otp = rand(100000, 999999);
    $expiresAt = now()->addMinutes(5);

    // Send OTP via email (using Laravel Mail)
    try {
        Mail::raw("Your password reset OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset OTP');
        });
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to send email. Check mail settings.']);
    }

    // Store temporarily in session
    session([
        'reset_email' => $request->email,
        'reset_otp' => $otp,
        'reset_expires_at' => $expiresAt,
    ]);

    return response()->json(['success' => true, 'message' => 'OTP sent to your email!']);
}
}
