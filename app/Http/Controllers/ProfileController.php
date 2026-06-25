<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $member = DB::table('members')->where('id', session('member_id'))->first();

        return view('member.profile', compact('member'));
    }

    public function update(Request $request)
    {
        $memberId = session('member_id');

        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:members,email,' . $memberId,
            'address'        => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
        ]);

        DB::table('members')->where('id', $memberId)->update([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'address'        => $validated['address'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'updated_at'     => now(),
        ]);

        session(['member_name' => $validated['first_name'] . ' ' . $validated['last_name']]);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $memberId = session('member_id');

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $member = DB::table('members')->where('id', $memberId)->first();

        if (!Hash::check($validated['current_password'], $member->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::table('members')->where('id', $memberId)->update([
            'password' => Hash::make($validated['new_password']),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}