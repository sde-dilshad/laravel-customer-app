<?php

namespace App\Http\Controllers;

use App\Mail\MfaCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'User registered'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $code = 121212;//rand(100000, 999999);
        Cache::put('mfa_code_' . $user->id, $code, now()->addMinutes(10));

        Mail::to($user->email)->send(new MfaCodeMail( $code));

        Session::put('mfa_email', $user->email);

        return redirect()->route('mfa.form');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out']);
    }

    public function showMfaForm()
    {
        return Inertia::render('auth/MfaForm', [
            'email' => Session::get('mfa_email')
        ]);
    }

    public function verifyMfa(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 422);
        }

        $cachedCode = Cache::get('mfa_code_' . $user->id);

        if (!$cachedCode || $cachedCode != $request->code) {
            return response()->json(['message' => 'Invalid or expired MFA code'], 422);

        }

        Cache::forget('mfa_code_' . $user->id);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        auth()->login($user);

        return response()->json([
            'token' => $token
        ]);
    }

}
