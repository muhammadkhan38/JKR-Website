@extends('layouts.admin')

@section('title', 'Manage Users')
@section('heading', 'Manage Users')

@section('content')
<div class="rounded-md border border-slate-200 bg-white shadow-sm">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Bookmarks</th><th>Role</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->bookmarks_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex gap-2">
                            @csrf @method('PUT')
                            <select name="role" class="rounded-md border border-slate-300 px-2 py-1 text-sm">
                                <option value="user" @selected($user->role === 'user')>User</option>
                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            </select>
                            <button class="admin-action">Save</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete>
                            @csrf @method('DELETE')
                            <button class="admin-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
