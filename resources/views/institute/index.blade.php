@extends('layouts.app')

@section('title', $settings['madrasa_name'] ?? __('messages.jamia.institute_title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ $settings['madrasa_name'] ?? __('messages.jamia.institute_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-white/90">{{ $settings['short_about'] ?? __('messages.footer.default_about') }}</p>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 pt-14 sm:px-6 lg:px-8">
    <h2 class="section-heading">{{ __('messages.jamia.about_title') }}</h2>
    <p class="section-copy">{{ $settings['short_about'] ?? __('messages.footer.default_about') }}</p>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <h2 class="section-heading">{{ __('messages.home.important_categories') }}</h2>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="course-card">
                <div class="course-card__icon" aria-hidden="true">📚</div>
                <h2>{{ $category->localized_name }}</h2>
                <p class="course-card__duration">{{ __('messages.common.books_count', ['count' => $category->books_count]) }}</p>
                @if($category->localized_description)
                    <p class="course-card__copy">{{ $category->localized_description }}</p>
                @endif
            </a>
        @empty
            <p class="surface-card p-6 text-stone-600">{{ __('messages.books.empty') }}</p>
        @endforelse
    </div>
</section>

<section class="home-information">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <h2 class="section-heading">{{ __('messages.jamia.our_teachers') }}</h2>
        <div class="mt-8 grid gap-7 md:grid-cols-3">
            @forelse($teachers as $teacher)
                <a href="{{ route('books.index', ['author' => $teacher->id]) }}" class="teacher-card">
                    <div class="teacher-avatar" aria-hidden="true">{{ Str::substr($teacher->localized_name, 0, 1) }}</div>
                    <div class="teacher-card__body">
                        <h2>{{ $teacher->localized_name }}</h2>
                        <p class="teacher-card__title">{{ __('messages.common.books_count', ['count' => $teacher->books_count]) }}</p>
                        @if($teacher->localized_bio)
                            <p class="teacher-card__copy">{{ $teacher->localized_bio }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="surface-card p-6 text-stone-600">{{ __('messages.books.empty') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
