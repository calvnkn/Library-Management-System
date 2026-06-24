<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = DB::table('admins')->where('id', session('admin_id'))->first();

        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $adminId = session('admin_id');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $adminId,
        ]);

        DB::table('admins')->where('id', $adminId)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now(),
        ]);

        session(['admin_name' => $validated['name']]);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $adminId = session('admin_id');

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $admin = DB::table('admins')->where('id', $adminId)->first();

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::table('admins')->where('id', $adminId)->update([
            'password' => Hash::make($validated['new_password']),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}