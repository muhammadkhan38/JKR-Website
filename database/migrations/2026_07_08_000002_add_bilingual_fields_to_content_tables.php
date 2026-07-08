<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            if (! Schema::hasColumn('categories', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
                $table->string('name_ur')->nullable()->after('name_en');
                $table->text('description_en')->nullable()->after('description');
                $table->text('description_ur')->nullable()->after('description_en');
            }
        });

        Schema::table('authors', function (Blueprint $table): void {
            if (! Schema::hasColumn('authors', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
                $table->string('name_ur')->nullable()->after('name_en');
                $table->text('bio_en')->nullable()->after('bio');
                $table->text('bio_ur')->nullable()->after('bio_en');
            }
        });

        Schema::table('books', function (Blueprint $table): void {
            if (! Schema::hasColumn('books', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
                $table->string('title_ur')->nullable()->after('title_en');
                $table->string('language_en')->nullable()->after('language');
                $table->string('language_ur')->nullable()->after('language_en');
                $table->text('short_description_en')->nullable()->after('short_description');
                $table->text('short_description_ur')->nullable()->after('short_description_en');
                $table->longText('description_en')->nullable()->after('description');
                $table->longText('description_ur')->nullable()->after('description_en');
                $table->string('pdf_file_en')->nullable()->after('pdf_file');
                $table->string('pdf_file_ur')->nullable()->after('pdf_file_en');
            }
        });

        Schema::table('islamic_events', function (Blueprint $table): void {
            if (! Schema::hasColumn('islamic_events', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
                $table->string('title_ur')->nullable()->after('title_en');
                $table->text('description_en')->nullable()->after('description');
                $table->text('description_ur')->nullable()->after('description_en');
            }
        });

        Schema::table('audios', function (Blueprint $table): void {
            if (! Schema::hasColumn('audios', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
                $table->string('title_ur')->nullable()->after('title_en');
                $table->string('speaker_en')->nullable()->after('speaker');
                $table->string('speaker_ur')->nullable()->after('speaker_en');
                $table->text('description_en')->nullable()->after('description');
                $table->text('description_ur')->nullable()->after('description_en');
            }
        });

        $this->copyLegacyValues();
    }

    public function down(): void
    {
        Schema::table('audios', function (Blueprint $table): void {
            $table->dropColumn(['title_en', 'title_ur', 'speaker_en', 'speaker_ur', 'description_en', 'description_ur']);
        });

        Schema::table('islamic_events', function (Blueprint $table): void {
            $table->dropColumn(['title_en', 'title_ur', 'description_en', 'description_ur']);
        });

        Schema::table('books', function (Blueprint $table): void {
            $table->dropColumn([
                'title_en',
                'title_ur',
                'language_en',
                'language_ur',
                'short_description_en',
                'short_description_ur',
                'description_en',
                'description_ur',
                'pdf_file_en',
                'pdf_file_ur',
            ]);
        });

        Schema::table('authors', function (Blueprint $table): void {
            $table->dropColumn(['name_en', 'name_ur', 'bio_en', 'bio_ur']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['name_en', 'name_ur', 'description_en', 'description_ur']);
        });
    }

    private function copyLegacyValues(): void
    {
        DB::table('categories')->update([
            'name_en' => DB::raw('COALESCE(name_en, name)'),
            'name_ur' => DB::raw('COALESCE(name_ur, name)'),
            'description_en' => DB::raw('COALESCE(description_en, description)'),
            'description_ur' => DB::raw('COALESCE(description_ur, description)'),
        ]);

        DB::table('authors')->update([
            'name_en' => DB::raw('COALESCE(name_en, name)'),
            'name_ur' => DB::raw('COALESCE(name_ur, name)'),
            'bio_en' => DB::raw('COALESCE(bio_en, bio)'),
            'bio_ur' => DB::raw('COALESCE(bio_ur, bio)'),
        ]);

        DB::table('books')->update([
            'title_en' => DB::raw('COALESCE(title_en, title)'),
            'title_ur' => DB::raw('COALESCE(title_ur, title)'),
            'language_en' => DB::raw('COALESCE(language_en, language)'),
            'language_ur' => DB::raw('COALESCE(language_ur, language)'),
            'short_description_en' => DB::raw('COALESCE(short_description_en, short_description)'),
            'short_description_ur' => DB::raw('COALESCE(short_description_ur, short_description)'),
            'description_en' => DB::raw('COALESCE(description_en, description)'),
            'description_ur' => DB::raw('COALESCE(description_ur, description)'),
        ]);

        DB::table('islamic_events')->update([
            'title_en' => DB::raw('COALESCE(title_en, title)'),
            'title_ur' => DB::raw('COALESCE(title_ur, title)'),
            'description_en' => DB::raw('COALESCE(description_en, description)'),
            'description_ur' => DB::raw('COALESCE(description_ur, description)'),
        ]);

        DB::table('audios')->update([
            'title_en' => DB::raw('COALESCE(title_en, title)'),
            'title_ur' => DB::raw('COALESCE(title_ur, title)'),
            'speaker_en' => DB::raw('COALESCE(speaker_en, speaker)'),
            'speaker_ur' => DB::raw('COALESCE(speaker_ur, speaker)'),
            'description_en' => DB::raw('COALESCE(description_en, description)'),
            'description_ur' => DB::raw('COALESCE(description_ur, description)'),
        ]);
    }
};
