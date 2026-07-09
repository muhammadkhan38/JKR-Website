<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MuftiAhmadMumtazBookSeeder extends Seeder
{
    private const ARCHIVE_IDENTIFIER = 'Maktaba-Mufti-Ahmad-Mumtaz';

    private const FILES = [
        'Aath-Masail.pdf',
        'Adiya-Nafia.pdf',
        'Ahkam-i-Haiz-wa-Nafaas-wa-Istihaza-Arabic-Ahkam-al-Haiz-wal-Nafaas-wal-Istihaza.pdf',
        'Ahkam-i-Haiz-wa-Nafaas-wa-Istihaza-Urdu.pdf',
        'Asli-Zeenat.pdf',
        'Chaar-Masail-Arabic-Abhath-Fiqhiyyah-Fi-Arbaa-Qazaya-Maasarah.pdf',
        'Chaar-Masail-Urdu.pdf',
        'Currency-Aur-Hundi-wa-Hawala-Kay-Karobar-Ki-Shari-Haisiyyat.pdf',
        'Darhi-Ahur-Munchon-Ka-Hukam.pdf',
        'Dars-i-Irshad-al-Sarf.pdf',
        'Dars-i-Nahw-Meer.pdf',
        'Digital-Tasveer-Aur-TV-Channel-Kay-Zariyai-Tableegh-Ka-Hukam.pdf',
        'Digital-Tasveer-Aur-Video-Ki-Hurmat-Par-300-Say-Zaid-Fatawajaat-wa-Tahreeraat-Aur-Jamhoor-Ulama-i-Karam-Ka-Moqif.pdf',
        'Digital-Tasveer-Say-Mutalliq-Mudallal-Fatwa-Arabic.pdf',
        'Dora-i-Tijarat-Mukhtasar.pdf',
        'Ghair-Muqaliddeen-Ka-Asli-Chehra.pdf',
        'Ghair-Muqaliddeen-Say-Chand-Usool-wa-Zwabit-Ki-Wazahat-Ka-Mutaliba.pdf',
        'Ghair-Soodi-Bankari-Aik-Munsifana-Ilmi-Jaizah.pdf',
        'Hajj-wa-Umrah-Mai-Khawateen-Kay-Masail-i-Makhsoosah.pdf',
        'Haram-Zarai-Amdan-Aur-Un-Ki-Murawwajah-Sooratain.pdf',
        'Hawa-Parasti-Ka-Khatarnak-Anjam.pdf',
        'Hayya-Alal-Falah-Par-Qiyam-Ka-Masala.pdf',
        'Heela-i-Isqat-Aur-Dua-Bad-Namaz-i-Janaza-Ka-Hukam.pdf',
        'Ibadur-Rahman-Kay-Ausaaf.pdf',
        'Ihtiyati-Daimi-Naqsha-i-Awqat-Namaz-wa-Sahar-wa-Iftar.pdf',
        'Ijara-i-Banookia.pdf',
        'Imam-i-Azam-Kay-Dilchap-Waqiat.pdf',
        'Intihayi-Mufeed-Dua-Aur-Hasanah-Ki-Tareef.pdf',
        'Islami-Bankari.pdf',
        'Islam-Ki-Haqeeqat-Aur-Sunnat-wa-Bidat-Ki-Wazahat.pdf',
        'Istishara-wa-Istikhara-Ki-Ahmiyyat.pdf',
        'Kapray-Morr-Kar-Takhnay-Kholay-Rakhnay-Ka-Hukam.pdf',
        'Khawateen-Ka-Asli-Zewar.pdf',
        'Masail-i-Ramazan.pdf',
        'Munfarid-Aur-Muqtadi-Ki-Namaz-Aur-Qiraat-Ka-Hukam.pdf',
        'Murawwajah-Takaful-Aur-Sharai-Waqf.pdf',
        'Murawwaja-Islami-Bankari-Say-Mutalliq-Mudallal-Fatwa-Arabic.pdf',
        'Murawwaja-Islami-Bankari-Say-Mutalliq-Mudallal-Fatwa-Urdu.pdf',
        'Murawwaja-Takaful-Say-Mutalliq-Mudallal-Fatwa-Arabic.pdf',
        'Murawwaja-Takaful-Say-Mutalliq-Mudallal-Fatwa-Urdu.pdf',
        'Murwwajah-Tijarti-Companian-Aur-Islami-Shirkat-wa-Muzarabat.pdf',
        'Musalman-Tajir.pdf',
        'Panch-Masail.pdf',
        'Qurbani-Kay-Fazail-wa-Masail.pdf',
        'Qurbani-Kay-Fazail-wa-Masail-Mudallal.pdf',
        'Talaq-i-Salas-Aik-Sawal-Ka-Jawab.pdf',
        'Taqwa-Kay-Char-Inamaat.pdf',
        'Tasveer-Ki-Mukhtalif-Aqsam-Aur-Aik-Musalman-Ki-Zimmadari.pdf',
        'Tohfa-i-Salihaat-Yani-Tasheel-Ahkam-i-Haiz-wa-Nafaas.pdf',
        'TV-Par-Tableegh-Say-Mutalliq-Mudallal-Fatwa.pdf',
        'Walidain-Aur-Aulaad-Kay-Huqooq.pdf',
    ];

    public function run(): void
    {
        $category = Category::updateOrCreate(
            ['slug' => 'mufti-ahmad-mumtaz-books'],
            [
                'name' => 'Mufti Ahmad Mumtaz Books',
                'name_en' => 'Mufti Ahmad Mumtaz Books',
                'name_ur' => 'مفتی احمد ممتاز صاحب کی کتب',
                'description' => 'A public PDF library of Mufti Ahmad Mumtaz Sahib books.',
                'description_en' => 'A public PDF library of Mufti Ahmad Mumtaz Sahib books.',
                'description_ur' => 'مفتی احمد ممتاز صاحب کی پی ڈی ایف کتب کا عوامی مجموعہ۔',
                'is_active' => true,
            ]
        );

        $author = Author::updateOrCreate(
            ['slug' => 'mufti-ahmad-mumtaz-sahib'],
            [
                'name' => 'Mufti Ahmad Mumtaz Sahib',
                'name_en' => 'Mufti Ahmad Mumtaz Sahib',
                'name_ur' => 'مفتی احمد ممتاز صاحب',
                'bio' => 'Author of the Mufti Ahmad Mumtaz Sahib PDF books collection.',
                'bio_en' => 'Author of the Mufti Ahmad Mumtaz Sahib PDF books collection.',
                'bio_ur' => 'مفتی احمد ممتاز صاحب کی پی ڈی ایف کتب کے مصنف۔',
            ]
        );

        foreach (self::FILES as $index => $fileName) {
            $baseName = Str::beforeLast($fileName, '.pdf');
            $title = Str::of($baseName)->replace('-', ' ')->headline()->toString();
            $url = $this->archiveDownloadUrl($fileName);
            $language = Str::contains($fileName, 'Arabic') ? 'Arabic' : 'Urdu';

            Book::updateOrCreate(
                ['slug' => 'mufti-ahmad-mumtaz-'.Str::slug($baseName)],
                [
                    'title' => $title,
                    'title_en' => $title,
                    'title_ur' => 'کتاب: '.$title,
                    'author_id' => $author->id,
                    'category_id' => $category->id,
                    'language' => $language,
                    'language_en' => $language,
                    'language_ur' => $language === 'Arabic' ? 'عربی' : 'اردو',
                    'short_description' => 'A PDF from the Mufti Ahmad Mumtaz Sahib books collection.',
                    'short_description_en' => 'A PDF from the Mufti Ahmad Mumtaz Sahib books collection.',
                    'short_description_ur' => 'مفتی احمد ممتاز صاحب کی کتب کے مجموعے سے ایک PDF کتاب۔',
                    'description' => 'Sourced from the public Google Drive folder "Mufti Ahmad mumtaz Sahib PDF books" and mirrored on Internet Archive for stable online reading.',
                    'description_en' => 'Sourced from the public Google Drive folder "Mufti Ahmad mumtaz Sahib PDF books" and mirrored on Internet Archive for stable online reading.',
                    'description_ur' => 'یہ کتاب عوامی Google Drive فولڈر "Mufti Ahmad mumtaz Sahib PDF books" سے لی گئی ہے اور آسان آن لائن مطالعے کے لیے Internet Archive پر موجود آئینے سے دکھائی جا رہی ہے۔',
                    'external_pdf_url' => $url,
                    'external_pdf_url_en' => $url,
                    'external_pdf_url_ur' => $url,
                    'is_latest' => false,
                    'is_featured' => $index < 6,
                    'is_active' => true,
                    'download_allowed' => true,
                ]
            );
        }
    }

    private function archiveDownloadUrl(string $fileName): string
    {
        return sprintf(
            'https://archive.org/download/%s/%s',
            self::ARCHIVE_IDENTIFIER,
            rawurlencode($fileName)
        );
    }
}
