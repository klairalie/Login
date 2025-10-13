<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerificationEmailCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Anhskohbo\NoCaptcha\NoCaptcha;

class CodeEmailVerifyController extends Controller
{
    public function showCodeForm()
    {
        return view('process.verify_code');
    }

    public function submitCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $data = session('registration_data');

        if (!$data) {
            return redirect()->route('register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        if ($request->code != $data['verification_code']) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birthdate' => $data['birthdate'],
            'age' => $data['age'],
            'address' => $data['address'], // fixed from birthdate
            'contact' => $data['contact'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'user_type' => $data['user_type'],
            'email_verified_at' => now(),
        ]);

        session()->forget('registration_data'); // only forget this, not all

        Auth::login($user);

        return redirect()->route('show.login')->with('success', 'Your email has been verified.');
    }

    public function resendCode()
    {
        $data = session('registration_data');

        if (!$data) {
            return redirect()->route('register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $newCode = rand(100000, 999999);

        $data['verification_code'] = $newCode;
        session(['registration_data' => $data]);

        Mail::to($data['email'])->send(new VerificationEmailCode((object)[
            'username' => $data['username'],
            'email' => $data['email'],
            'verification_code' => $newCode,
        ]));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
