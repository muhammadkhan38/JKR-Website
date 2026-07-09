@extends('layouts.admin')

@section('title', __('messages.admin.books.add'))
@section('heading', __('messages.admin.books.add'))

@section('content')
<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="admin-panel p-5 sm:p-6">
    @include('admin.books._form')
</form>
@endsection
