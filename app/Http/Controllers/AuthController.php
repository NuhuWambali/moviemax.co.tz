<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    
    public function showRegister()
    {
        return view('auth.register');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email'); 
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ])->onlyInput('email');
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isSystemUser()) {
            return redirect()->intended('/admin');
        }

        $redirect = $request->input('redirect');
        if ($redirect && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
            return redirect($redirect);
        }

        return redirect()->intended('/profile');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_admin'  => false,
            'user_type' => 'user',
            'is_active' => true,
        ]);
        
        Auth::login($user);
        
        return redirect('/profile');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function redirectToGoogle()
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login')->withErrors(['email' => 'Google sign in is not configured yet. Please try again later.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        if ($request->input('error')) {
            return redirect()->route('login')->withErrors(['email' => 'Google sign in was cancelled or failed.']);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to sign in with Google. Please try again.']);
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));

        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Google did not return an email address.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $name = trim((string) $googleUser->getName()) ?: $email;
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make(Str::random(40)),
                'is_admin'  => false,
                'user_type' => 'user',
                'is_active' => true,
            ]);
        }

        if (!$user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isSystemUser()) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/profile');
    }
}