<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders google drive preview with a pdfjs fallback', function (): void {
    $category = Category::create([
        'name' => 'Fiqh',
        'name_en' => 'Fiqh',
        'slug' => 'fiqh',
        'is_active' => true,
    ]);

    $author = Author::create([
        'name' => 'Scholar',
        'name_en' => 'Scholar',
        'slug' => 'scholar',
    ]);

    $book = Book::create([
        'title' => 'Sample Book',
        'title_en' => 'Sample Book',
        'slug' => 'sample-book',
        'author_id' => $author->id,
        'category_id' => $category->id,
        'language' => 'English',
        'language_en' => 'English',
        'external_pdf_url' => 'https://drive.google.com/file/d/abc_123-XYZ/view?usp=sharing',
        'external_pdf_url_en' => 'https://drive.google.com/file/d/abc_123-XYZ/view?usp=sharing',
        'is_active' => true,
        'download_allowed' => true,
    ]);

    $this->get(route('books.reader', $book))
        ->assertOk()
        ->assertSee('https://drive.google.com/file/d/abc_123-XYZ/preview', false)
        ->assertSee(rawurlencode(route('books.pdf', $book)), false)
        ->assertSee(route('books.download', $book), false);
});

it('shows a friendly message when the pdf link is invalid', function (): void {
    $category = Category::create([
        'name' => 'Hadith',
        'name_en' => 'Hadith',
        'slug' => 'hadith',
        'is_active' => true,
    ]);

    $author = Author::create([
        'name' => 'Teacher',
        'name_en' => 'Teacher',
        'slug' => 'teacher',
    ]);

    $book = Book::create([
        'title' => 'Broken Link Book',
        'title_en' => 'Broken Link Book',
        'slug' => 'broken-link-book',
        'author_id' => $author->id,
        'category_id' => $category->id,
        'language' => 'English',
        'language_en' => 'English',
        'external_pdf_url' => 'https://example.com/not-a-pdf',
        'is_active' => true,
        'download_allowed' => true,
    ]);

    $this->get(route('books.reader', $book))
        ->assertOk()
        ->assertSee(__('messages.books.pdf_unavailable'));
});
