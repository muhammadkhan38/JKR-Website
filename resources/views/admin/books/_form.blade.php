@csrf
@if($book->exists)
    @method('PUT')
@endif
<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="title_en">{{ __('messages.admin.fields.title_en') }}</label>
        <input class="form-input" id="title_en" name="title_en" value="{{ old('title_en', $book->title_en ?: $book->title) }}" required>
    </div>
    <div>
        <label class="form-label" for="title_ur">{{ __('messages.admin.fields.title_ur') }}</label>
        <input class="form-input" id="title_ur" name="title_ur" value="{{ old('title_ur', $book->title_ur) }}" dir="rtl">
    </div>
    <div>
        <label class="form-label" for="language_en">{{ __('messages.admin.fields.language_en') }}</label>
        <input class="form-input" id="language_en" name="language_en" value="{{ old('language_en', $book->language_en ?: $book->language ?: __('messages.language.english')) }}" required>
    </div>
    <div>
        <label class="form-label" for="language_ur">{{ __('messages.admin.fields.language_ur') }}</label>
        <input class="form-input" id="language_ur" name="language_ur" value="{{ old('language_ur', $book->language_ur ?: __('messages.language.urdu')) }}" dir="rtl">
    </div>
    <div>
        <label class="form-label" for="author_id">{{ __('messages.admin.fields.author') }}</label>
        <select class="form-input" id="author_id" name="author_id" required>
            <option value="">{{ __('messages.admin.fields.author') }}</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->localized_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="category_id">{{ __('messages.admin.fields.category') }}</label>
        <select class="form-input" id="category_id" name="category_id" required>
            <option value="">{{ __('messages.admin.fields.category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->localized_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="short_description_en">{{ __('messages.admin.fields.short_description_en') }}</label>
        <textarea class="form-input" id="short_description_en" name="short_description_en" rows="3">{{ old('short_description_en', $book->short_description_en ?: $book->short_description) }}</textarea>
    </div>
    <div>
        <label class="form-label" for="short_description_ur">{{ __('messages.admin.fields.short_description_ur') }}</label>
        <textarea class="form-input" id="short_description_ur" name="short_description_ur" rows="3" dir="rtl">{{ old('short_description_ur', $book->short_description_ur) }}</textarea>
    </div>
</div>
<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="description_en">{{ __('messages.admin.fields.description_en') }}</label>
        <textarea class="form-input" id="description_en" name="description_en" rows="7">{{ old('description_en', $book->description_en ?: $book->description) }}</textarea>
    </div>
    <div>
        <label class="form-label" for="description_ur">{{ __('messages.admin.fields.description_ur') }}</label>
        <textarea class="form-input" id="description_ur" name="description_ur" rows="7" dir="rtl">{{ old('description_ur', $book->description_ur) }}</textarea>
    </div>
</div>
<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <div>
        <label class="form-label" for="cover_image">{{ __('messages.admin.fields.cover_image') }}</label>
        <input class="form-input" id="cover_image" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" data-file-label="#cover-file-name" data-cover-preview="#cover-preview">
        <p id="cover-file-name" class="mt-2 text-sm text-slate-500"></p>
        @if($book->cover_url)
            <img id="cover-preview" src="{{ $book->cover_url }}" alt="{{ $book->localized_title }}" class="mt-3 h-32 rounded-md object-cover">
        @else
            <img id="cover-preview" src="" alt="" class="hidden mt-3 h-32 rounded-md object-cover">
        @endif
    </div>
    <div>
        <label class="form-label" for="pdf_file">{{ __('messages.admin.books.fallback_pdf') }}</label>
        <input class="form-input" id="pdf_file" type="file" name="pdf_file" accept=".pdf" data-file-label="#pdf-file-name">
        <p id="pdf-file-name" class="mt-2 text-sm text-slate-500">{{ $book->pdf_file ? __('messages.common.current_file', ['file' => $book->pdf_file]) : '' }}</p>
    </div>
    <div>
        <label class="form-label" for="pdf_file_en">{{ __('messages.admin.books.english_pdf') }}</label>
        <input class="form-input" id="pdf_file_en" type="file" name="pdf_file_en" accept=".pdf" data-file-label="#pdf-en-file-name">
        <p id="pdf-en-file-name" class="mt-2 text-sm text-slate-500">{{ $book->pdf_file_en ? __('messages.common.current_file', ['file' => $book->pdf_file_en]) : '' }}</p>
    </div>
    <div>
        <label class="form-label" for="pdf_file_ur">{{ __('messages.admin.books.urdu_pdf') }}</label>
        <input class="form-input" id="pdf_file_ur" type="file" name="pdf_file_ur" accept=".pdf" data-file-label="#pdf-ur-file-name">
        <p id="pdf-ur-file-name" class="mt-2 text-sm text-slate-500">{{ $book->pdf_file_ur ? __('messages.common.current_file', ['file' => $book->pdf_file_ur]) : '' }}</p>
    </div>
</div>
<div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
    @foreach([
        'is_latest' => [__('messages.books.latest_only'), false],
        'is_featured' => [__('messages.books.featured_only'), false],
        'is_active' => [__('messages.common.active'), true],
        'download_allowed' => [__('messages.common.download_pdf'), true],
    ] as $field => [$label, $default])
        <label class="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-3 py-3 text-sm font-medium text-slate-700">
            <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $book->exists ? $book->{$field} : $default))>
            {{ $label }}
        </label>
    @endforeach
</div>
<div class="mt-6 flex gap-3">
    <button class="rounded-md bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">{{ $book->exists ? __('messages.admin.books.update_button') : __('messages.admin.books.create_button') }}</button>
    <a href="{{ route('admin.books.index') }}" class="rounded-md border border-slate-200 px-5 py-3 font-semibold text-slate-700 hover:bg-slate-50">{{ __('messages.common.cancel') }}</a>
</div>
