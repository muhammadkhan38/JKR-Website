@extends('layouts.admin')

@section('title', 'کتب کا انتظام')
@section('heading', 'کتب کا انتظام')
@section('actions')
    <a href="{{ route('admin.books.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">کتاب شامل کریں</a>
@endsection

@section('content')
<div class="rounded-md border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead><tr><th>کتاب</th><th>مصنف</th><th>زمرہ</th><th>نشانات</th><th>عمل</th></tr></thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-950">{{ $book->title }}</div>
                            <div class="text-xs text-slate-500">{{ $book->language }}، {{ $book->created_at->format('d-m-Y') }}</div>
                        </td>
                        <td>{{ $book->author?->name }}</td>
                        <td>{{ $book->category?->name }}</td>
                        <td class="space-x-1">
                            @if($book->is_active)<span class="status-badge bg-emerald-100 text-emerald-800">فعال</span>@endif
                            @if($book->is_featured)<span class="status-badge bg-amber-100 text-amber-800">منتخب</span>@endif
                            @if($book->is_latest)<span class="status-badge bg-sky-100 text-sky-800">نئی</span>@endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.books.edit', $book) }}" class="admin-action">ترمیم</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" data-confirm-delete>
                                    @csrf @method('DELETE')
                                    <button class="admin-danger" type="submit">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">ابھی کوئی کتاب اپ لوڈ نہیں کی گئی۔</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $books->links() }}</div>
</div>
@endsection
