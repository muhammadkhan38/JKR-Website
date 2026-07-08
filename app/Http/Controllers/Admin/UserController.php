<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::withCount('bookmarks')->latest()->paginate(20)]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.users.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('admin.users.index');
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $user->update($data);

        return back()->with('success', 'User role updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
