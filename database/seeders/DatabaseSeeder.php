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
            ['name' => 'Admin', 'password' => 'password123', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'student@madrasa.test'],
            ['name' => 'Student', 'password' => 'password123', 'role' => 'user']
        );

        $categories = collect([
            'quran-studies' => [
                'name_en' => 'Quran Studies',
                'name_ur' => 'علوم قرآن',
                'description_en' => 'Books about recitation, tafsir, and understanding the Quran.',
                'description_ur' => 'قرآن کریم کی تلاوت، تفسیر اور فہم سے متعلق کتب۔',
            ],
            'hadith' => [
                'name_en' => 'Hadith',
                'name_ur' => 'حدیث',
                'description_en' => 'Selected hadith collections with accessible explanations.',
                'description_ur' => 'منتخب احادیث اور ان کی آسان تشریحات۔',
            ],
            'fiqh' => [
                'name_en' => 'Fiqh',
                'name_ur' => 'فقہ',
                'description_en' => 'Practical rulings for everyday Muslim life.',
                'description_ur' => 'روزمرہ زندگی کے عملی شرعی احکام۔',
            ],
            'seerah' => [
                'name_en' => 'Seerah',
                'name_ur' => 'سیرت',
                'description_en' => 'Books on the blessed life and character of the Prophet ﷺ.',
                'description_ur' => 'رسول اللہ ﷺ کی مبارک سیرت اور اخلاق سے متعلق کتب۔',
            ],
            'duas' => [
                'name_en' => 'Duas',
                'name_ur' => 'دعائیں',
                'description_en' => 'Masnoon duas and daily adhkar.',
                'description_ur' => 'مسنون دعائیں اور روزانہ کے اذکار۔',
            ],
        ])->mapWithKeys(function (array $data, string $slug) {
            return [$slug => Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name_en'],
                    'description' => $data['description_en'],
                    'is_active' => true,
                ] + $data
            )];
        });

        $authors = collect([
            'madrasa-scholars' => [
                'name_en' => 'Madrasa Scholars',
                'name_ur' => 'علمائے مدرسہ',
                'bio_en' => 'Material prepared and reviewed under the supervision of madrasa teachers.',
                'bio_ur' => 'مدرسہ کے اساتذہ کی نگرانی میں تیار اور نظرِ ثانی شدہ مواد۔',
            ],
            'mufti-abdul-hakeem' => [
                'name_en' => 'Mufti Abdul Hakeem',
                'name_ur' => 'مفتی عبدال حکیم',
                'bio_en' => 'Teacher of fiqh and hadith.',
                'bio_ur' => 'فقہ اور حدیث کے استاد۔',
            ],
            'maulana-rashid-ahmad' => [
                'name_en' => 'Maulana Rashid Ahmad',
                'name_ur' => 'مولانا راشد احمد',
                'bio_en' => 'Speaker and Islamic studies teacher.',
                'bio_ur' => 'مقرر اور اسلامیات کے مدرس۔',
            ],
        ])->mapWithKeys(function (array $data, string $slug) {
            return [$slug => Author::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name_en'],
                    'bio' => $data['bio_en'],
                ] + $data
            )];
        });

        $bookRows = [
            [
                'slug' => 'ramadan-guide-for-students',
                'title_en' => 'Ramadan Guide for Students',
                'title_ur' => 'طلبہ کے لیے رمضان گائیڈ',
                'author' => 'madrasa-scholars',
                'category' => 'fiqh',
                'language_en' => 'Urdu',
                'language_ur' => 'اردو',
                'short_description_en' => 'A short guide to fasting, taraweeh, zakat, and the etiquettes of Ramadan.',
                'short_description_ur' => 'روزہ، تراویح، زکات اور رمضان کے آداب پر مختصر رہنما کتاب۔',
                'is_latest' => true,
                'is_featured' => true,
            ],
            [
                'slug' => 'selected-daily-duas',
                'title_en' => 'Selected Daily Duas',
                'title_ur' => 'منتخب روزانہ دعائیں',
                'author' => 'madrasa-scholars',
                'category' => 'duas',
                'language_en' => 'Arabic / Urdu',
                'language_ur' => 'عربی / اردو',
                'short_description_en' => 'Daily duas for students and families with simple meanings.',
                'short_description_ur' => 'طلبہ اور گھر والوں کے لیے روزانہ کی دعائیں آسان معانی کے ساتھ۔',
                'is_latest' => true,
            ],
            [
                'slug' => 'introduction-to-seerah',
                'title_en' => 'Introduction to Seerah',
                'title_ur' => 'سیرت کا تعارف',
                'author' => 'maulana-rashid-ahmad',
                'category' => 'seerah',
                'language_en' => 'Urdu',
                'language_ur' => 'اردو',
                'short_description_en' => 'An introductory study of the blessed life of the Prophet ﷺ.',
                'short_description_ur' => 'رسول اللہ ﷺ کی مبارک زندگی کو سمجھنے کے لیے ابتدائی مطالعہ۔',
                'is_featured' => true,
            ],
            [
                'slug' => 'hajj-and-umrah-basics',
                'title_en' => 'Hajj and Umrah Basics',
                'title_ur' => 'حج و عمرہ کے بنیادی مسائل',
                'author' => 'mufti-abdul-hakeem',
                'category' => 'fiqh',
                'language_en' => 'Urdu',
                'language_ur' => 'اردو',
                'short_description_en' => 'Simple rulings and reminders for Hajj and Umrah travel.',
                'short_description_ur' => 'حج اور عمرہ کے سفر کے لیے آسان احکام اور ضروری یاد دہانیاں۔',
                'is_featured' => true,
            ],
            [
                'slug' => 'hadith-for-character-building',
                'title_en' => 'Hadith for Character Building',
                'title_ur' => 'اخلاق سازی کے لیے احادیث',
                'author' => 'maulana-rashid-ahmad',
                'category' => 'hadith',
                'language_en' => 'Urdu',
                'language_ur' => 'اردو',
                'short_description_en' => 'Selected hadith on manners, trustworthiness, and service.',
                'short_description_ur' => 'آداب، دیانت اور خدمت کے موضوع پر مختصر منتخب احادیث۔',
                'is_latest' => true,
            ],
        ];

        $books = collect($bookRows)->mapWithKeys(function (array $row) use ($categories, $authors) {
            $descriptionEn = $row['short_description_en']."\n\nUpload the complete PDF from the admin panel to enable online reading.";
            $descriptionUr = $row['short_description_ur']."\n\nاس کتاب کو آن لائن پڑھنے کے قابل بنانے کے لیے انتظامی پینل سے مکمل PDF اپ لوڈ کریں۔";

            $book = Book::updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title_en'],
                    'title_en' => $row['title_en'],
                    'title_ur' => $row['title_ur'],
                    'author_id' => $authors[$row['author']]->id,
                    'category_id' => $categories[$row['category']]->id,
                    'language' => $row['language_en'],
                    'language_en' => $row['language_en'],
                    'language_ur' => $row['language_ur'],
                    'short_description' => $row['short_description_en'],
                    'short_description_en' => $row['short_description_en'],
                    'short_description_ur' => $row['short_description_ur'],
                    'description' => $descriptionEn,
                    'description_en' => $descriptionEn,
                    'description_ur' => $descriptionUr,
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
                'title' => 'Ramadan Collection',
                'title_en' => 'Ramadan Collection',
                'title_ur' => 'رمضان مجموعہ',
                'description' => 'Books about fasting, zakat, taraweeh, and preparing for Ramadan.',
                'description_en' => 'Books about fasting, zakat, taraweeh, and preparing for Ramadan.',
                'description_ur' => 'روزہ، زکات، تراویح اور رمضان کی تیاری سے متعلق کتب۔',
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
                'title' => 'Seerah Collection',
                'title_en' => 'Seerah Collection',
                'title_ur' => 'سیرت مجموعہ',
                'description' => 'Selected study material about the life, mercy, and character of the Prophet ﷺ.',
                'description_en' => 'Selected study material about the life, mercy, and character of the Prophet ﷺ.',
                'description_ur' => 'رسول اللہ ﷺ کی حیاتِ مبارکہ، رحمت اور اخلاق کے بارے میں منتخب مطالعہ۔',
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
                'title' => 'Preparing for Ramadan',
                'title_en' => 'Preparing for Ramadan',
                'title_ur' => 'رمضان کی تیاری',
                'speaker' => 'Mufti Abdul Hakeem',
                'speaker_en' => 'Mufti Abdul Hakeem',
                'speaker_ur' => 'مفتی عبدال حکیم',
                'description' => 'A short lecture description. Upload an MP3 from the admin panel to enable playback.',
                'description_en' => 'A short lecture description. Upload an MP3 from the admin panel to enable playback.',
                'description_ur' => 'مختصر بیان کی تفصیل۔ آڈیو چلانے کے لیے انتظامی پینل سے MP3 اپ لوڈ کریں۔',
                'book_id' => $books['ramadan-guide-for-students']->id,
                'category_id' => $categories['fiqh']->id,
                'islamic_event_id' => $ramadan->id,
                'duration' => '18:00',
                'is_active' => true,
            ]
        );

        $this->call(MuftiAhmadMumtazBookSeeder::class);

        foreach ([
            'madrasa_name' => 'Madrasa Islamic Books',
            'madrasa_name_en' => 'Madrasa Islamic Books',
            'madrasa_name_ur' => 'مدرسہ اسلامی کتب',
            'contact_number' => '+92 300 0000000',
            'whatsapp_number' => '+92 300 0000000',
            'email' => 'info@madrasa.test',
            'address' => 'Madrasa office, Main Road',
            'address_en' => 'Madrasa office, Main Road',
            'address_ur' => 'مدرسہ دفتر، مین روڈ',
            'short_about' => 'A simple madrasa library for selected Islamic books, seasonal collections, saved lists, and audio lessons.',
            'short_about_en' => 'A simple madrasa library for selected Islamic books, seasonal collections, saved lists, and audio lessons.',
            'short_about_ur' => 'منتخب اسلامی کتب، موقع کی مناسبت سے مجموعوں، محفوظ فہرست اور آڈیو اسباق کے لیے ایک سادہ مدرسہ لائبریری۔',
            'footer_text' => 'Serving beneficial knowledge with simplicity and respect.',
            'footer_text_en' => 'Serving beneficial knowledge with simplicity and respect.',
            'footer_text_ur' => 'سادگی اور احترام کے ساتھ نفع بخش علم کی خدمت۔',
            'facebook_link' => null,
            'youtube_link' => null,
            'logo' => null,
            'homepage_banner' => null,
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
