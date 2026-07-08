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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@madrasa.test'],
            ['name' => 'منتظم', 'password' => 'password123', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'student@madrasa.test'],
            ['name' => 'طالب علم', 'password' => 'password123', 'role' => 'user']
        );

        $categories = collect([
            'quran-studies' => ['name' => 'علومِ قرآن', 'description' => 'قرآن کریم کی تلاوت، تفسیر اور فہم سے متعلق کتب۔'],
            'hadith' => ['name' => 'حدیث', 'description' => 'منتخب احادیث اور ان کی آسان تشریحات۔'],
            'fiqh' => ['name' => 'فقہ', 'description' => 'روزمرہ زندگی کے عملی شرعی احکام۔'],
            'seerah' => ['name' => 'سیرت', 'description' => 'رسول اللہ ﷺ کی مبارک سیرت اور اخلاق سے متعلق کتب۔'],
            'duas' => ['name' => 'دعائیں', 'description' => 'مسنون دعائیں اور روزانہ کے اذکار۔'],
        ])->mapWithKeys(fn ($data, $slug) => [
            $data['name'] => Category::updateOrCreate(
                ['slug' => $slug],
                $data + ['is_active' => true]
            ),
        ]);

        $authors = collect([
            'madrasa-scholars' => ['name' => 'علمائے مدرسہ', 'bio' => 'مدرسہ کے اساتذہ کی نگرانی میں تیار اور نظرِ ثانی شدہ مواد۔'],
            'mufti-abdul-hakeem' => ['name' => 'مفتی عبدالحکیم', 'bio' => 'فقہ اور حدیث کے استاد۔'],
            'maulana-rashid-ahmad' => ['name' => 'مولانا راشد احمد', 'bio' => 'مقرر اور اسلامیات کے مدرس۔'],
        ])->mapWithKeys(fn ($data, $slug) => [
            $data['name'] => Author::updateOrCreate(
                ['slug' => $slug],
                $data
            ),
        ]);

        $bookRows = [
            [
                'slug' => 'ramadan-guide-for-students',
                'title' => 'طلبہ کے لیے رمضان گائیڈ',
                'author' => 'علمائے مدرسہ',
                'category' => 'فقہ',
                'language' => 'اردو',
                'short_description' => 'روزہ، تراویح، زکات اور رمضان کے آداب پر مختصر رہنما کتاب۔',
                'is_latest' => true,
                'is_featured' => true,
            ],
            [
                'slug' => 'selected-daily-duas',
                'title' => 'منتخب روزانہ دعائیں',
                'author' => 'علمائے مدرسہ',
                'category' => 'دعائیں',
                'language' => 'عربی / اردو',
                'short_description' => 'طلبہ اور گھر والوں کے لیے روزانہ کی دعائیں آسان معانی کے ساتھ۔',
                'is_latest' => true,
            ],
            [
                'slug' => 'introduction-to-seerah',
                'title' => 'سیرت کا تعارف',
                'author' => 'مولانا راشد احمد',
                'category' => 'سیرت',
                'language' => 'اردو',
                'short_description' => 'رسول اللہ ﷺ کی مبارک زندگی کو سمجھنے کے لیے ابتدائی مطالعہ۔',
                'is_featured' => true,
            ],
            [
                'slug' => 'hajj-and-umrah-basics',
                'title' => 'حج و عمرہ کے بنیادی مسائل',
                'author' => 'مفتی عبدالحکیم',
                'category' => 'فقہ',
                'language' => 'اردو',
                'short_description' => 'حج اور عمرہ کے سفر کے لیے آسان احکام اور ضروری یاد دہانیاں۔',
                'is_featured' => true,
            ],
            [
                'slug' => 'hadith-for-character-building',
                'title' => 'اخلاق سازی کے لیے احادیث',
                'author' => 'مولانا راشد احمد',
                'category' => 'حدیث',
                'language' => 'اردو',
                'short_description' => 'آداب، دیانت اور خدمت کے موضوع پر مختصر منتخب احادیث۔',
                'is_latest' => true,
            ],
        ];

        $books = collect($bookRows)->mapWithKeys(function (array $row) use ($categories, $authors) {
            $book = Book::updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'author_id' => $authors[$row['author']]->id,
                    'category_id' => $categories[$row['category']]->id,
                    'language' => $row['language'],
                    'short_description' => $row['short_description'],
                    'description' => $row['short_description']."\n\nاس کتاب کو آن لائن پڑھنے کے قابل بنانے کے لیے انتظامی پینل سے مکمل PDF اپ لوڈ کریں۔",
                    'is_latest' => $row['is_latest'] ?? false,
                    'is_featured' => $row['is_featured'] ?? false,
                    'is_active' => true,
                    'download_allowed' => true,
                ]
            );

            return [$row['slug'] => $book];
        });

        $ramadan = IslamicEvent::updateOrCreate(
            ['slug' => 'ramadan-collection'],
            [
                'title' => 'رمضان مجموعہ',
                'description' => 'روزہ، زکات، تراویح اور رمضان کی تیاری سے متعلق کتب۔',
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'is_active' => true,
                'display_order' => 1,
            ]
        );
        $ramadan->books()->sync([$books['ramadan-guide-for-students']->id, $books['selected-daily-duas']->id]);

        $seerah = IslamicEvent::updateOrCreate(
            ['slug' => 'seerah-collection'],
            [
                'title' => 'سیرت مجموعہ',
                'description' => 'رسول اللہ ﷺ کی حیاتِ مبارکہ، رحمت اور اخلاق کے بارے میں منتخب مطالعہ۔',
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
                'display_order' => 2,
            ]
        );
        $seerah->books()->sync([$books['introduction-to-seerah']->id, $books['hadith-for-character-building']->id]);

        Audio::updateOrCreate(
            ['slug' => 'ramadan-preparation-lecture'],
            [
                'title' => 'رمضان کی تیاری',
                'speaker' => 'مفتی عبدالحکیم',
                'description' => 'مختصر بیان کی تفصیل۔ آڈیو چلانے کے لیے انتظامی پینل سے MP3 اپ لوڈ کریں۔',
                'book_id' => $books['ramadan-guide-for-students']->id,
                'category_id' => $categories['فقہ']->id,
                'islamic_event_id' => $ramadan->id,
                'duration' => '18:00',
                'is_active' => true,
            ]
        );

        foreach ([
            'madrasa_name' => 'مدرسہ اسلامی کتب',
            'contact_number' => '+92 300 0000000',
            'whatsapp_number' => '+92 300 0000000',
            'email' => 'info@madrasa.test',
            'address' => 'مدرسہ دفتر، مین روڈ',
            'short_about' => 'منتخب اسلامی کتب، موقع کی مناسبت سے مجموعوں، محفوظ فہرست اور آڈیو اسباق کے لیے ایک سادہ مدرسہ لائبریری۔',
            'footer_text' => 'سادگی اور احترام کے ساتھ نفع بخش علم کی خدمت۔',
            'facebook_link' => null,
            'youtube_link' => null,
            'logo' => null,
            'homepage_banner' => null,
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
