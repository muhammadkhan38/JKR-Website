<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed();
});

test('the institute pages render database-backed content', function (): void {
    $pages = [
        'lectures.index' => 'Preparing for Ramadan',
        'live.index' => 'Preparing for Ramadan',
        'institute.index' => 'Madrasa Scholars',
        'announcements.index' => 'Ramadan Collection',
    ];

    foreach ($pages as $route => $content) {
        $this->get(route($route))
            ->assertOk()
            ->assertSee($content)
            ->assertSee('Language');
    }
});
