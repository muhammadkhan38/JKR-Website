@extends('layouts.admin')

@section('title', 'انتظامی ڈیش بورڈ')
@section('heading', 'ڈیش بورڈ')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([
        ['کل کتب', $totalBooks],
        ['زمرہ جات', $totalCategories],
        ['مصنفین', $totalAuthors],
        ['صارفین', $totalUsers],
        ['آڈیو فائلیں', $totalAudios],
        ['فعال مجموعے', $activeEvents],
    ] as [$label, $value])
        <div class="rounded-md border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 rounded-md border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
        <h2 class="text-lg font-bold text-slate-950">تازہ اپ لوڈ کی گئی کتب</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead><tr><th>عنوان</th><th>مصنف</th><th>زمرہ</th><th>حالت</th><th>اپ لوڈ تاریخ</th></tr></thead>
            <tbody>
                @forelse($latestBooks as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author?->name }}</td>
                        <td>{{ $book->category?->name }}</td>
                        <td>{{ $book->is_active ? 'فعال' : 'غیر فعال' }}</td>
                        <td>{{ $book->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">ابھی کوئی کتاب موجود نہیں۔</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
