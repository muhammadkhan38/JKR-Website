@extends('layouts.admin')

@section('title', 'Add Book')
@section('heading', 'Add Book')

@section('content')
<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="rounded-md border border-slate-200 bg-white p-6 shadow-sm">
    @include('admin.books._form')
</form>
@endsection
