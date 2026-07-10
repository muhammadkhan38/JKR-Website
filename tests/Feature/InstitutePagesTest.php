<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed();
});

test('the institute reference pages render their complete content', function (): void {
    $pages = [
        'lectures.index' => 'Tafsir of Surah Al-Kahf',
        'live.index' => 'The Importance of Seeking Knowledge',
        'institute.index' => 'Dars-e-Nizami (Alim Course)',
        'announcements.index' => 'Admissions Open for 2026–27',
    ];

    foreach ($pages as $route => $content) {
        $this->get(route($route))
            ->assertOk()
            ->assertSee($content)
            ->assertSee('Language');
    }
});
