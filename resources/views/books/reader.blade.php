@extends('layouts.app')

@section('title', $book->title.' کا مطالعہ')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="section-kicker">آن لائن ریڈر</p>
            <h1 class="text-2xl font-bold text-emerald-950">{{ $book->title }}</h1>
        </div>
        <a href="{{ route('books.show', $book) }}" class="rounded-md border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">تفصیل پر واپس جائیں</a>
    </div>
    @if($book->pdf_url)
        <div class="overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
            <iframe src="{{ $book->pdf_url }}" class="h-[75vh] min-h-[560px] w-full" title="{{ $book->title }} PDF ریڈر"></iframe>
        </div>
    @else
        <div class="rounded-md border border-amber-200 bg-amber-50 p-6 text-amber-900">PDF فائل ابھی دستیاب نہیں۔</div>
    @endif
</section>
@endsection
