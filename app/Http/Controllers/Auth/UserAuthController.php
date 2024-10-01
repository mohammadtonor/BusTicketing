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

    public function showProfile()
    {
        return view('frontend.user-profile');
    }

    public function updateProfile(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'national_num' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
        ]);

        // Update the user information
        $user = Auth::user();
        $nameParts = explode(' ', $request->input('name'), 2);
        $user->first_name = $nameParts[0];
        $user->last_name = $nameParts[1] ?? '';
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->national_num = $request->input('national_num');
        $user->gender = $request->input('gender');
        $user->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Your information has been updated successfully!');
    }
}
