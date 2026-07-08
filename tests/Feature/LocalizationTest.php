<?php

use App\Models\Audio;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('english is the default locale for the public website', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('dir="ltr"', false);
    $response->assertSee('Language');
    $response->assertSee('Islamic Books Library');
    $response->assertSee('Ramadan Guide for Students');
});

test('urdu locale renders rtl layout and localized content', function () {
    $response = $this->withSession(['locale' => 'ur'])->get('/');

    $response->assertOk();
    $response->assertSee('dir="rtl"', false);
    $response->assertSee('زبان');
    $response->assertSee('اسلامی کتب کی لائبریری');
    $response->assertSee('طلبہ کے لیے رمضان گائیڈ');
});

test('language switch stores the selected locale', function () {
    $response = $this->from('/')->get('/language/ur');

    $response->assertRedirect('/');
    $response->assertSessionHas('locale', 'ur');
    $response->assertCookie('locale', 'ur');
});

test('unsupported languages fall back to english', function () {
    $response = $this->from('/')->get('/language/fr');

    $response->assertRedirect('/');
    $response->assertSessionHas('locale', 'en');
});

test('book search works in english and urdu fields', function () {
    $this->get('/books?q=Ramadan')
        ->assertOk()
        ->assertSee('Ramadan Guide for Students');

    $this->withSession(['locale' => 'ur'])->get('/books?q=رمضان')
        ->assertOk()
        ->assertSee('طلبہ کے لیے رمضان گائیڈ');
});

test('translation catalogs and blade views have full english and urdu coverage', function () {
    $this->artisan('translations:sync', ['--check' => true])
        ->assertSuccessful();
});

test('database content locale sync detects urdu copied into english columns', function () {
    $author = Author::firstOrFail();
    $category = Category::firstOrFail();

    Book::create([
        'slug' => 'polluted-language-book',
        'title' => 'آزمائشی کتاب',
        'title_en' => 'آزمائشی کتاب',
        'title_ur' => 'آزمائشی کتاب',
        'author_id' => $author->id,
        'category_id' => $category->id,
        'language' => 'اردو',
        'language_en' => 'اردو',
        'language_ur' => 'اردو',
        'short_description' => 'مختصر وضاحت',
        'short_description_en' => 'مختصر وضاحت',
        'short_description_ur' => 'مختصر وضاحت',
        'description' => 'تفصیلی وضاحت',
        'description_en' => 'تفصیلی وضاحت',
        'description_ur' => 'تفصیلی وضاحت',
        'is_active' => true,
        'download_allowed' => true,
    ]);

    $this->artisan('content:sync-locales')
        ->expectsOutputToContain('polluted-language-book')
        ->assertSuccessful();
});

test('seeded database content locales are already clean', function () {
    $this->artisan('content:sync-locales')
        ->expectsOutput('Database content locales are already in sync.')
        ->assertSuccessful();
});

test('main website view routes render in english and urdu', function () {
    $book = Book::where('slug', 'ramadan-guide-for-students')->firstOrFail();
    $category = Category::where('slug', 'fiqh')->firstOrFail();
    $event = IslamicEvent::where('slug', 'ramadan-collection')->firstOrFail();
    $audio = Audio::where('slug', 'ramadan-preparation-lecture')->firstOrFail();
    $author = Author::where('slug', 'madrasa-scholars')->firstOrFail();
    $student = User::where('email', 'student@madrasa.test')->firstOrFail();
    $admin = User::where('email', 'admin@madrasa.test')->firstOrFail();

    $publicRoutes = [
        route('home', [], false),
        route('books.index', [], false),
        route('books.show', $book, false),
        route('books.reader', $book, false),
        route('categories.show', $category, false),
        route('events.show', $event, false),
        route('audios.index', [], false),
        route('contact', [], false),
        route('login', [], false),
        route('register', [], false),
    ];

    $userRoutes = [
        route('bookmarks.index', [], false),
        route('profile.edit', [], false),
    ];

    $adminRoutes = [
        route('admin.dashboard', [], false),
        route('admin.books.index', [], false),
        route('admin.books.create', [], false),
        route('admin.books.edit', $book, false),
        route('admin.categories.index', [], false),
        route('admin.categories.edit', $category, false),
        route('admin.authors.index', [], false),
        route('admin.authors.edit', $author, false),
        route('admin.islamic-events.index', [], false),
        route('admin.islamic-events.edit', $event, false),
        route('admin.audios.index', [], false),
        route('admin.audios.edit', $audio, false),
        route('admin.users.index', [], false),
        route('admin.settings.index', [], false),
    ];

    foreach (['en' => 'ltr', 'ur' => 'rtl'] as $locale => $direction) {
        foreach ($publicRoutes as $uri) {
            $this->withSession(['locale' => $locale])
                ->get($uri)
                ->assertOk()
                ->assertSee('dir="'.$direction.'"', false)
                ->assertSee($locale === 'ur' ? 'زبان' : 'Language');
        }

        foreach ($userRoutes as $uri) {
            $this->withSession(['locale' => $locale])
                ->actingAs($student)
                ->get($uri)
                ->assertOk()
                ->assertSee('dir="'.$direction.'"', false)
                ->assertSee($locale === 'ur' ? 'زبان' : 'Language');
        }

        foreach ($adminRoutes as $uri) {
            $this->withSession(['locale' => $locale])
                ->actingAs($admin)
                ->get($uri)
                ->assertOk()
                ->assertSee('dir="'.$direction.'"', false)
                ->assertSee($locale === 'ur' ? 'زبان' : 'Language');
        }
    }
});
