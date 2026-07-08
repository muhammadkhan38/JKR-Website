@extends('layouts.admin')

@section('title', 'صارفین کا انتظام')
@section('heading', 'صارفین کا انتظام')

@section('content')
<div class="rounded-md border border-slate-200 bg-white shadow-sm">
    <table class="admin-table">
        <thead><tr><th>نام</th><th>ای میل</th><th>محفوظ کتب</th><th>کردار</th><th>عمل</th></tr></thead>
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
                                <option value="user" @selected($user->role === 'user')>عام صارف</option>
                                <option value="admin" @selected($user->role === 'admin')>منتظم</option>
                            </select>
                            <button class="admin-action">محفوظ کریں</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete>
                            @csrf @method('DELETE')
                            <button class="admin-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
