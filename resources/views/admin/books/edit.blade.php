@extends('layouts.admin')

@section('title', 'Edit Book')
@section('heading', 'Edit Book')

@section('content')
<form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
    @include('admin.books._form')
</form>
@endsection
