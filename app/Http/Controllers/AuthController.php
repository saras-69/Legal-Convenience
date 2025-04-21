<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:citizen,lsp,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_verified' => false,
            'reward_points' => 0,
        ]);

        // Send verification OTP (implement this functionality)
        // sendOTP($user->email, $user->phone);

        Auth::login($user);

        if ($user->role === 'lsp') {
            return redirect()->route('lsp.profile.create');
        }

        return redirect()->route('home')->with('success', 'Registration successful! Please verify your account.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'lsp') {
                return redirect()->route('lsp.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Verify OTP logic (implement this functionality)
        $user = Auth::user();
        // if (verifyOTP($user, $request->otp)) {
        //     $user->is_verified = true;
        //     $user->save();
        //     return redirect()->route('home')->with('success', 'Account verified successfully!');
        // }

        // For demo purposes, we'll just mark the user as verified
        $user->is_verified = true;
        $user->save();
        return redirect()->route('home')->with('success', 'Account verified successfully!');

        return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
    }
}