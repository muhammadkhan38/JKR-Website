<?php

namespace Database\Seeders;

use App\Models\Audio;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\IslamicEvent;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@madrasa.test'],
            ['name' => 'Admin', 'password' => 'password123', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'student@madrasa.test'],
            ['name' => 'Student User', 'password' => 'password123', 'role' => 'user']
        );

        $categories = collect([
            ['name' => 'Quran Studies', 'description' => 'Books related to Quran recitation, tafsir, and understanding.'],
            ['name' => 'Hadith', 'description' => 'Selected Hadith collections and explanations.'],
            ['name' => 'Fiqh', 'description' => 'Practical Islamic rulings for daily life.'],
            ['name' => 'Seerah', 'description' => 'Biography and character of Prophet Muhammad, peace be upon him.'],
            ['name' => 'Duas', 'description' => 'Supplications and daily remembrances.'],
        ])->mapWithKeys(fn ($data) => [
            $data['name'] => Category::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                $data + ['is_active' => true]
            ),
        ]);

        $authors = collect([
            ['name' => 'Madrasa Scholars', 'bio' => 'Prepared and reviewed by the Madrasa teaching staff.'],
            ['name' => 'Mufti Abdul Hakeem', 'bio' => 'Teacher of Fiqh and Hadith.'],
            ['name' => 'Maulana Rashid Ahmad', 'bio' => 'Speaker and Islamic studies instructor.'],
        ])->mapWithKeys(fn ($data) => [
            $data['name'] => Author::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                $data
            ),
        ]);

        $bookRows = [
            [
                'title' => 'Ramadan Guide for Students',
                'author' => 'Madrasa Scholars',
                'category' => 'Fiqh',
                'language' => 'Urdu',
                'short_description' => 'A concise guide to fasting, taraweeh, zakat, and Ramadan manners.',
                'is_latest' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Selected Daily Duas',
                'author' => 'Madrasa Scholars',
                'category' => 'Duas',
                'language' => 'Arabic / Urdu',
                'short_description' => 'Daily duas with simple meanings for students and families.',
                'is_latest' => true,
            ],
            [
                'title' => 'Introduction to Seerah',
                'author' => 'Maulana Rashid Ahmad',
                'category' => 'Seerah',
                'language' => 'English',
                'short_description' => 'An introductory reading plan for learning the blessed life of the Prophet.',
                'is_featured' => true,
            ],
            [
                'title' => 'Hajj and Umrah Basics',
                'author' => 'Mufti Abdul Hakeem',
                'category' => 'Fiqh',
                'language' => 'Urdu',
                'short_description' => 'Simple rulings and reminders for Hajj and Umrah journeys.',
                'is_featured' => true,
            ],
            [
                'title' => 'Hadith for Character Building',
                'author' => 'Maulana Rashid Ahmad',
                'category' => 'Hadith',
                'language' => 'English',
                'short_description' => 'Short Hadith selections focused on manners, honesty, and service.',
                'is_latest' => true,
            ],
        ];

        $books = collect($bookRows)->mapWithKeys(function (array $row) use ($categories, $authors) {
            $book = Book::updateOrCreate(
                ['slug' => Str::slug($row['title'])],
                [
                    'title' => $row['title'],
                    'author_id' => $authors[$row['author']]->id,
                    'category_id' => $categories[$row['category']]->id,
                    'language' => $row['language'],
                    'short_description' => $row['short_description'],
                    'description' => $row['short_description']."\n\nUpload the full PDF from the admin panel to make this book readable online.",
                    'is_latest' => $row['is_latest'] ?? false,
                    'is_featured' => $row['is_featured'] ?? false,
                    'is_active' => true,
                    'download_allowed' => true,
                ]
            );

            return [$row['title'] => $book];
        });

        $ramadan = IslamicEvent::updateOrCreate(
            ['slug' => 'ramadan-collection'],
            [
                'title' => 'Ramadan Collection',
                'description' => 'Books for fasting, zakat, taraweeh, and Ramadan preparation.',
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'is_active' => true,
                'display_order' => 1,
            ]
        );
        $ramadan->books()->sync([$books['Ramadan Guide for Students']->id, $books['Selected Daily Duas']->id]);

        $seerah = IslamicEvent::updateOrCreate(
            ['slug' => 'seerah-collection'],
            [
                'title' => 'Seerah Collection',
                'description' => 'Readings about the life, mercy, and character of the Prophet, peace be upon him.',
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
                'display_order' => 2,
            ]
        );
        $seerah->books()->sync([$books['Introduction to Seerah']->id, $books['Hadith for Character Building']->id]);

        Audio::updateOrCreate(
            ['slug' => 'ramadan-preparation-lecture'],
            [
                'title' => 'Preparing for Ramadan',
                'speaker' => 'Mufti Abdul Hakeem',
                'description' => 'A short lecture note. Upload MP3 from admin to enable playback.',
                'book_id' => $books['Ramadan Guide for Students']->id,
                'category_id' => $categories['Fiqh']->id,
                'islamic_event_id' => $ramadan->id,
                'duration' => '18:00',
                'is_active' => true,
            ]
        );

        foreach ([
            'madrasa_name' => 'Madrasa Islamic Books',
            'contact_number' => '+92 300 0000000',
            'whatsapp_number' => '+92 300 0000000',
            'email' => 'info@madrasa.test',
            'address' => 'Madrasa Office, Main Road',
            'short_about' => 'A simple Madrasa library for selected books, event collections, bookmarks, and audio lessons.',
            'footer_text' => 'Serving beneficial knowledge with simplicity and respect.',
            'facebook_link' => null,
            'youtube_link' => null,
            'logo' => null,
            'homepage_banner' => null,
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
