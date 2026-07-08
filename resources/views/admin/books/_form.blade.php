@csrf
@if($book->exists)
    @method('PUT')
@endif
<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="title">Title</label>
        <input class="form-input" id="title" name="title" value="{{ old('title', $book->title) }}" required>
    </div>
    <div>
        <label class="form-label" for="language">Language</label>
        <input class="form-input" id="language" name="language" value="{{ old('language', $book->language ?: 'Urdu') }}" required>
    </div>
    <div>
        <label class="form-label" for="author_id">Author</label>
        <select class="form-input" id="author_id" name="author_id" required>
            <option value="">Choose author</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="category_id">Category</label>
        <select class="form-input" id="category_id" name="category_id" required>
            <option value="">Choose category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mt-5">
    <label class="form-label" for="short_description">Short Description</label>
    <textarea class="form-input" id="short_description" name="short_description" rows="3">{{ old('short_description', $book->short_description) }}</textarea>
</div>
<div class="mt-5">
    <label class="form-label" for="description">Full Description</label>
    <textarea class="form-input" id="description" name="description" rows="7">{{ old('description', $book->description) }}</textarea>
</div>
<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="cover_image">Cover Image</label>
        <input class="form-input" id="cover_image" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" data-file-label="#cover-file-name" data-cover-preview="#cover-preview">
        <p id="cover-file-name" class="mt-2 text-sm text-slate-500"></p>
        @if($book->cover_url)
            <img id="cover-preview" src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="mt-3 h-32 rounded-md object-cover">
        @else
            <img id="cover-preview" src="" alt="" class="hidden mt-3 h-32 rounded-md object-cover">
        @endif
    </div>
    <div>
        <label class="form-label" for="pdf_file">PDF File</label>
        <input class="form-input" id="pdf_file" type="file" name="pdf_file" accept=".pdf" data-file-label="#pdf-file-name" @required(! $book->exists)>
        <p id="pdf-file-name" class="mt-2 text-sm text-slate-500">{{ $book->pdf_file ? 'Current: '.$book->pdf_file : '' }}</p>
    </div>
</div>
<div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
    @foreach([
        'is_latest' => ['Latest', false],
        'is_featured' => ['Featured', false],
        'is_active' => ['Active', true],
        'download_allowed' => ['Allow Download', true],
    ] as $field => [$label, $default])
        <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-3 py-3 text-sm font-medium text-slate-700">
            <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $book->exists ? $book->{$field} : $default))>
            {{ $label }}
        </label>
    @endforeach
</div>
<div class="mt-6 flex gap-3">
    <button class="rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">{{ $book->exists ? 'Update Book' : 'Create Book' }}</button>
    <a href="{{ route('admin.books.index') }}" class="rounded-md border border-slate-200 px-5 py-3 font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
</div>
