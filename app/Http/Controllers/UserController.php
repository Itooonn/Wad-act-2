<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $users = User::query()->latest()->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => (bool) ($validated['is_admin'] ?? false),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->authorizeUserView($user);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_admin = (bool) ($validated['is_admin'] ?? false);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        abort_if(auth()->id() === $user->id, 422, 'You cannot delete your own account.');

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    private function authorizeUserView(User $user): void
    {
        $currentUser = auth()->user();

        abort_unless(
            $currentUser?->isAdmin() || $currentUser?->is($user),
            403
        );
    }
}
