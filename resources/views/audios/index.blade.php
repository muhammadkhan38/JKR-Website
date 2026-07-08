@extends('layouts.app')

@section('title', 'Audio Lectures')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <p class="section-kicker">Listen</p>
        <h1 class="section-heading">Audio Lectures</h1>
    </div>
    <form method="GET" class="mb-8 flex gap-3 rounded-md border border-emerald-100 bg-white p-4">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search audio by title, speaker, description..." class="form-input flex-1">
        <button class="rounded-md bg-emerald-700 px-4 py-3 font-semibold text-white hover:bg-emerald-800">Search</button>
    </form>
    <div class="grid gap-4 lg:grid-cols-2">
        @forelse($audios as $audio)
            @include('partials.audio-card', ['audio' => $audio])
        @empty
            <p class="rounded-md border border-dashed border-emerald-200 p-6 text-stone-600">No audio lectures found.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $audios->links() }}</div>
</section>
@endsection
