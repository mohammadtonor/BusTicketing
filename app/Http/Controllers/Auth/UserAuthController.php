<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }


    // Handle the user login process
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'password'));
    }

    // Show the user registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        // Validate the registration form data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:15',
        ]);

        // Create the new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'gender' => $request->gender,
            'phone' => $request->phone,
            'role' => 'passenger', // Example roles like 'user' or 'admin'
        ]);

        // Log the user in after registration
        Auth::login($user);

        return redirect()->intended('/')->with('success', 'Welcome! Your account has been created.');
    }

    // Handle the user logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
