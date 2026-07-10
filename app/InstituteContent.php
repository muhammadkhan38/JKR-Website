<?php

namespace App;

class InstituteContent
{
    /**
     * @return array{upcoming: list<array<string, string>>, weekly: list<array<string, string>>, tours: list<array{city: string, dates: string, events: list<array<string, string>>}>}
     */
    public function lectures(): array
    {
        return [
            'upcoming' => [
                ['day' => '17', 'month' => 'Jul', 'topic' => 'Tafsir of Surah Al-Kahf', 'speaker' => 'Mufti Abdul Raheem', 'time' => 'After Maghrib', 'city' => 'Karachi', 'venue' => 'Jamia Main Hall'],
                ['day' => '19', 'month' => 'Jul', 'topic' => 'Rights of Parents in Islam', 'speaker' => 'Maulana Yusuf Qasmi', 'time' => '11:00 AM', 'city' => 'Hyderabad', 'venue' => 'Madni Masjid'],
                ['day' => '25', 'month' => 'Jul', 'topic' => 'Seerah Conference', 'speaker' => 'All senior teachers', 'time' => '9:00 AM', 'city' => 'Lahore', 'venue' => 'Jamia Ashrafia Hall'],
                ['day' => '29', 'month' => 'Jul', 'topic' => 'Fiqh of Trade & Business', 'speaker' => 'Mufti Abdul Raheem', 'time' => '8:00 PM', 'city' => 'Karachi', 'venue' => 'Jamia Main Hall'],
            ],
            'weekly' => [
                ['day' => 'Daily', 'time' => 'After Fajr', 'title' => 'Dars-e-Quran', 'teacher' => 'Mufti Abdul Raheem', 'venue' => 'Jamia Masjid'],
                ['day' => 'Mon & Thu', 'time' => 'After Isha', 'title' => 'Dars of Sahih al-Bukhari', 'teacher' => 'Maulana Yusuf Qasmi', 'venue' => 'Hall No. 2'],
                ['day' => 'Friday', 'time' => 'After Maghrib', 'title' => 'Weekly Bayan (open to public)', 'teacher' => 'Rotating teachers', 'venue' => 'Jamia Main Hall'],
                ['day' => 'Saturday', 'time' => '10:00 AM', 'title' => 'Tajweed Class for Adults', 'teacher' => 'Qari Imran Siddiqui', 'venue' => 'Hifz Department'],
            ],
            'tours' => [
                [
                    'city' => 'Lahore',
                    'dates' => '24–26 July 2026',
                    'events' => [
                        ['when' => 'Fri 24, Maghrib', 'title' => 'Bayan: Unity of the Ummah', 'speaker' => 'Mufti Abdul Raheem', 'venue' => 'Badshahi Area Masjid'],
                        ['when' => 'Sat 25, 9 AM', 'title' => 'Seerah Conference', 'speaker' => 'All senior teachers', 'venue' => 'Jamia Ashrafia Hall'],
                        ['when' => 'Sun 26, 11 AM', 'title' => 'Q&A Session with Students', 'speaker' => 'Maulana Yusuf Qasmi', 'venue' => 'Jamia Ashrafia Hall'],
                    ],
                ],
                [
                    'city' => 'Multan',
                    'dates' => '2–3 August 2026',
                    'events' => [
                        ['when' => 'Sun 2, Asr', 'title' => 'Bayan: Tarbiyah of Children', 'speaker' => 'Maulana Yusuf Qasmi', 'venue' => 'Hussain Agahi Masjid'],
                        ['when' => 'Mon 3, 10 AM', 'title' => 'Meeting with local ulama', 'speaker' => 'Mufti Abdul Raheem', 'venue' => 'Darul Uloom Multan'],
                    ],
                ],
                [
                    'city' => 'Faisalabad',
                    'dates' => '9 August 2026',
                    'events' => [
                        ['when' => 'Sun 9, Maghrib', 'title' => 'Bayan: The Blessing of Knowledge', 'speaker' => 'Mufti Abdul Raheem', 'venue' => 'Jamia Masjid Faisalabad'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array{recordings: list<array<string, string>>, live: array{title: string, speaker: string, started: string, listeners: string}}
     */
    public function live(): array
    {
        return [
            'live' => [
                'title' => '4 Masail',
                'speaker' => 'Mufti Abdul Raheem',
                'started' => 'after Maghrib',
                'listeners' => '124 listeners',
            ],
            'recordings' => [
                ['title' => 'The Importance of Seeking Knowledge', 'speaker' => 'Mufti Abdul Raheem', 'date' => '25 June 2026', 'duration' => '48 min'],
                ['title' => 'Tafsir Surah Yasin (Part 3)', 'speaker' => 'Mufti Abdul Raheem', 'date' => '19 June 2026', 'duration' => '61 min'],
                ['title' => 'Fiqh of Fasting — Q&A', 'speaker' => 'Maulana Yusuf Qasmi', 'date' => '12 June 2026', 'duration' => '39 min'],
                ['title' => 'Manners of the Student of Knowledge', 'speaker' => 'Maulana Yusuf Qasmi', 'date' => '5 June 2026', 'duration' => '55 min'],
                ['title' => 'Tajweed: Common Mistakes in Recitation', 'speaker' => 'Qari Imran Siddiqui', 'date' => '29 May 2026', 'duration' => '42 min'],
            ],
        ];
    }

    /**
     * @return array{courses: list<array<string, string>>, teachers: list<array<string, string>>}
     */
    public function institute(): array
    {
        return [
            'courses' => [
                ['icon' => '📖', 'name' => 'Hifz-ul-Quran', 'duration' => '3–4 years · Full time', 'description' => 'Complete memorization of the Quran with tajweed, under daily supervision of the Hifz department.'],
                ['icon' => '🎵', 'name' => 'Nazra & Tajweed', 'duration' => '1 year · Part time', 'description' => 'Correct reading of the Quran with the rules of tajweed, for children and adults.'],
                ['icon' => '🎓', 'name' => 'Dars-e-Nizami (Alim Course)', 'duration' => '8 years · Full time', 'description' => 'The classical curriculum: Arabic, Tafsir, Hadith, Fiqh, and the Islamic sciences, leading to the Alim degree.'],
                ['icon' => '🌙', 'name' => 'Evening Short Courses', 'duration' => 'Weekends · Open to all', 'description' => 'Short courses on essential beliefs, worship, and everyday fiqh for the general public.'],
            ],
            'teachers' => [
                ['initials' => 'AR', 'name' => 'Mufti Abdul Raheem', 'title' => 'Shaykh al-Hadith & Principal', 'bio' => 'Leads the dars of Hadith and Tafsir at the Jamia. Author of several published works in the library, and delivers the Friday bayan.'],
                ['initials' => 'YQ', 'name' => 'Maulana Yusuf Qasmi', 'title' => 'Senior Teacher — Fiqh & Seerah', 'bio' => 'Teaches Islamic jurisprudence and Seerah to Alim course students, and regularly travels for lectures in other cities.'],
                ['initials' => 'IS', 'name' => 'Qari Imran Siddiqui', 'title' => 'Head of Hifz Department', 'bio' => 'Supervises Hifz-ul-Quran students and teaches Tajweed and Qira’at, including weekend classes open to the public.'],
            ],
        ];
    }

    /**
     * @return list<array{type: string, title: string, body: string, date: string}>
     */
    public function announcements(): array
    {
        return [
            [
                'type' => 'Admission',
                'title' => 'Admissions Open for 2026–27',
                'body' => 'Admissions for the new academic year are now open. Students can apply for Hifz, Nazra, and Alim courses. Visit the Jamia office or contact us for the admission form and requirements. Last date to apply: 15 August 2026.',
                'date' => '1 July 2026',
            ],
            [
                'type' => 'New Bayan',
                'title' => 'New Bayan: The Importance of Seeking Knowledge',
                'body' => 'A new bayan by Mufti Abdul Raheem on the virtues of seeking knowledge in Islam is now available in the recordings library. Join us this Friday after Maghrib prayer in the main hall, or listen online.',
                'date' => '25 June 2026',
            ],
            [
                'type' => 'General',
                'title' => 'Library Timings for Summer',
                'body' => 'The library will remain open from 8:00 AM to 8:00 PM during the summer months. Students are encouraged to make use of the extended hours.',
                'date' => '10 June 2026',
            ],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    public function homeLectures(): array
    {
        return array_slice($this->lectures()['upcoming'], 0, 3);
    }

    /**
     * @return list<array{type: string, title: string, body: string, date: string}>
     */
    public function homeAnnouncements(): array
    {
        return array_slice($this->announcements(), 0, 2);
    }
}
