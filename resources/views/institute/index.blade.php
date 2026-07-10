@extends('layouts.app')

@section('title', __('messages.jamia.institute_title'))

@section('content')
<section class="page-hero">
    <div class="page-hero__content mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
        <h1 class="font-serif text-4xl font-bold sm:text-5xl">{{ __('messages.jamia.institute_title') }}</h1>
        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-white/90">{{ __('messages.jamia.institute_copy') }}</p>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 pt-14 sm:px-6 lg:px-8">
    <h2 class="section-heading">{{ __('messages.jamia.about_title') }}</h2>
    <p class="section-copy">{{ __('messages.jamia.about_copy') }}</p>
    <p class="mt-4 text-base leading-8 text-stone-600">{{ __('messages.jamia.about_second_copy') }}</p>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <h2 class="section-heading">{{ __('messages.jamia.courses_offered') }}</h2>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($courses as $course)
            <article class="course-card">
                <div class="course-card__icon" aria-hidden="true">{{ $course['icon'] }}</div>
                <h2>{{ $course['name'] }}</h2>
                <p class="course-card__duration">{{ $course['duration'] }}</p>
                <p class="course-card__copy">{{ $course['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="home-information">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <h2 class="section-heading">{{ __('messages.jamia.our_teachers') }}</h2>
        <div class="mt-8 grid gap-7 md:grid-cols-3">
            @foreach($teachers as $teacher)
                <article class="teacher-card">
                    <div class="teacher-avatar" aria-hidden="true">{{ $teacher['initials'] }}</div>
                    <div class="teacher-card__body">
                        <h2>{{ $teacher['name'] }}</h2>
                        <p class="teacher-card__title">{{ $teacher['title'] }}</p>
                        <p class="teacher-card__copy">{{ $teacher['bio'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
