<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $existing = DB::table('members')->where('email', $validated['email'])->first();

        if ($existing) {
            return back()->withErrors(['email' => 'Email is already registered.'])->withInput();
        }

        DB::table('members')->insert([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'address' => $validated['address'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. You may now log in.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $member = DB::table('members')->where('email', $credentials['email'])->first();

        if (!$member || !Hash::check($credentials['password'], $member->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        session([
            'member_id' => $member->id,
            'member_name' => trim(implode(' ', array_filter([$member->first_name, $member->middle_name, $member->last_name]))),
        ]);

        return redirect()->route('books.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['member_id', 'member_name']);
        return redirect()->route('login');
    }
}
