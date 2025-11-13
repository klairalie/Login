<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use App\Models\Login;
use Illuminate\Support\Facades\Mail;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Log;

class LoginRegController extends Controller
{
    public function showLogin()
    {
        return view('process.login');
    }

    public function login(Request $request)
    {
        // ✅ 1. Basic validation (email, password, otp, honeypot)
        $validated = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
            'otp'      => 'nullable|string',
            'nickname' => 'nullable|string', // honeypot field
        ]);

        // ✅ 2. Honeypot check (if bots fill this hidden field)
        if (!empty($validated['nickname'])) {
            // Optionally, you can log this attempt
            Log::warning('Bot detected by honeypot on login form.', [
                'ip' => $request->ip(),
                'email' => $validated['email'] ?? 'unknown',
                'timestamp' => now(),
            ]);

            // You can also delay response slightly to waste bot time
            sleep(2);
            return back()->withErrors([
                'email' => 'Suspicious activity detected. Please try again.',
            ]);
        }

        // ✅ 3. Optional: reCAPTCHA verification (server-side)
        if ($request->has('g-recaptcha-response')) {
            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . env('RECAPTCHA_SECRET_KEY') . '&response=' . $request->input('g-recaptcha-response'));
            $responseKeys = json_decode($response, true);
            if (!$responseKeys['success']) {
                return back()->withErrors(['email' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        // ✅ 4. Find user
        $user = Login::where('email', $validated['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Account not found.'],
            ]);
        }

        // ✅ 5. Check password
        $passwordValid = Hash::check($validated['password'], $user->password)
            || $user->password === $validated['password'];

        if (!$passwordValid) {
            throw ValidationException::withMessages([
                'email' => ['Invalid password.'],
            ]);
        }

        // ✅ 6. OTP Phase 1: Generate OTP if not provided
        if (empty($validated['otp'])) {
            $otp = rand(100000, 999999);

            session([
                'otp_code' => $otp,
                'otp_email' => $user->email,
                'otp_expires_at' => now()->addMinutes(5),
                'temp_email' => $validated['email'],
                'temp_password' => $validated['password'],
            ]);

            Mail::raw("Your OTP code is: {$otp}\nThis code expires in 5 minutes. from: 3RS Login System", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your OTP Code');
            });

            return back()->with([
                'otp_phase' => true,
                'status' => 'An OTP has been sent to your email. Please enter it to OTP input field to continue.',
            ]);
        }

        // ✅ 7. OTP Phase 2: Verify OTP
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

        // ✅ Clear OTP session
        session()->forget(['otp_code', 'otp_email', 'otp_expires_at', 'temp_email', 'temp_password']);

        // ✅ 8. Create login session
        session([
            'user_id'       => $user->id,
            'user_email'    => $user->email ?? $user->username,
            'user_position' => $user->position,
            'logged_in_at'  => now(),
        ]);

        session()->regenerate();
        session()->save();

        $tokenPayload = [
            'email'     => $user->email ?? $user->username,
            'position'  => $user->position,
            'timestamp' => now()->timestamp,
        ];

        $encryptedToken = Crypt::encryptString(json_encode($tokenPayload));
        $token = urlencode($encryptedToken);

        // ✅ 9. Log admin login
        if (in_array(strtolower($user->position), [
            'administrative manager',
            'human resource manager',
            'finance manager'
        ])) {
            AdminActivityLog::create([
                'actor_email' => $user->email,
                'target_email' => $user->email,
                'module' => ucfirst($user->position) . ' Authentication',
                'action' => 'Logged in',
                'changes' => json_encode(['timestamp' => now()]),
                'ip_address' => $request->ip(),
            ]);
        }

        // ✅ 10. Redirect by role
        switch (strtolower($user->position)) {
            case 'administrative manager':
                return redirect()->away("http://Capstone-Admin.test/auth/verify?token={$token}");
            case 'human resource manager':
                return redirect()->away("http://Humanresource.test/auth/verify?token={$token}");
            case 'finance manager':
                return redirect()->away("http://Capstone-Finance.test/auth/verify?token={$token}");
            default:
                return back()->withErrors([
                    'email' => 'Your position is not authorized or unrecognized.',
                ]);
        }
    }

    // ---------------- FORGOT PASSWORD ---------------- //
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

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        try {
            Mail::raw("Your password reset OTP is: $otp\nExpires in 5 minutes.", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Password Reset OTP');
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email.']);
        }

        session([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
            'reset_expires_at' => $expiresAt,
        ]);

        return response()->json(['success' => true, 'message' => 'OTP sent to your email!']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $sessionOtp = session('reset_otp');
        $expiresAt = session('reset_expires_at');
        $email = session('reset_email');

        if (
            !$sessionOtp ||
            now()->greaterThan($expiresAt) ||
            $request->otp !== (string)$sessionOtp ||
            $request->email !== $email
        ) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
        }

        $user = Login::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Account not found.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        if (in_array(strtolower($user->position), [
            'administrative manager',
            'human resource manager',
            'finance manager'
        ])) {
            AdminActivityLog::create([
                'actor_email' => $user->email,
                'target_email' => $user->email,
                'module' => ucfirst($user->position) . ' Account Management',
                'action' => 'Changed password',
                'changes' => json_encode(['updated_at' => now()]),
                'ip_address' => $request->ip(),
            ]);
        }

        session()->forget(['reset_email', 'reset_otp', 'reset_expires_at']);

        return response()->json(['success' => true, 'message' => 'Password has been successfully updated.']);
    }
}
