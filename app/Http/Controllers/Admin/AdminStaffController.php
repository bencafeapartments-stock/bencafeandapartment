<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staff = User::staff()->active()->get();
        return view('owner.staff.index', compact('staff'));
    }
    
    public function createStaff()
    {
        return view('owner.staff.create');
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact_number' => 'required|nullable|string|max:20||regex:/^09\d{9}$/',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'contact_number' => $request->contact_number,
            'is_active' => true,
        ]);

        return redirect()->route('owner.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function editStaff(User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }
        return view('owner.staff.edit', compact('user'));
    }

    public function updateStaff(Request $request, User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('owner.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function deleteStaff(User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }

        $user->update(['is_active' => false]);

        return redirect()->route('owner.staff.index')->with('success', 'Staff member deactivated successfully.');
    }
}
