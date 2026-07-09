@extends('layouts.admin')

@section('title', __('messages.admin.books.edit'))
@section('heading', __('messages.admin.books.edit'))

@section('content')
<form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="admin-panel p-5 sm:p-6">
    @include('admin.books._form')
</form>
@endsection
