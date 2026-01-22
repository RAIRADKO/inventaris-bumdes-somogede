<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('businessUnit')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(20);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        $roles = User::ROLE_LABELS;
        return view('user.create', compact('businessUnits', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:director,treasurer,unit_admin,supervisor,accountant',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()
            ->route('user.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['businessUnit', 'activityLogs' => fn($q) => $q->latest()->take(20)]);
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        $roles = User::ROLE_LABELS;
        return view('user.edit', compact('user', 'businessUnits', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:director,treasurer,unit_admin,supervisor,accountant',
            'business_unit_id' => 'nullable|exists:business_units,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()
            ->route('user.show', $user)
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Pengguna berhasil {$status}.");
    }
}
