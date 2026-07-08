@extends('layouts.admin')

@section('title', __('messages.admin.users.manage'))
@section('heading', __('messages.admin.users.manage'))

@section('content')
<div class="rounded-md border border-slate-200 bg-white shadow-sm">
    <table class="admin-table">
        <thead>
            <tr>
                <th>{{ __('messages.admin.fields.name') }}</th>
                <th>{{ __('messages.admin.fields.email') }}</th>
                <th>{{ __('messages.admin.users.saved_books') }}</th>
                <th>{{ __('messages.admin.users.role') }}</th>
                <th>{{ __('messages.admin.fields.action') }}</th>
            </tr>
        </thead>
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
                                <option value="user" @selected($user->role === 'user')>{{ __('messages.admin.users.regular_user') }}</option>
                                <option value="admin" @selected($user->role === 'admin')>{{ __('messages.admin.users.admin') }}</option>
                            </select>
                            <button class="admin-action">{{ __('messages.common.save') }}</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete>
                            @csrf @method('DELETE')
                            <button class="admin-danger">{{ __('messages.common.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
