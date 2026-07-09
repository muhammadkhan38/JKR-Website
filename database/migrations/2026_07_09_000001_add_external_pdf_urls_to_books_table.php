<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            if (! Schema::hasColumn('books', 'external_pdf_url')) {
                $table->text('external_pdf_url')->nullable()->after('pdf_file_ur');
                $table->text('external_pdf_url_en')->nullable()->after('external_pdf_url');
                $table->text('external_pdf_url_ur')->nullable()->after('external_pdf_url_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            if (Schema::hasColumn('books', 'external_pdf_url')) {
                $table->dropColumn(['external_pdf_url', 'external_pdf_url_en', 'external_pdf_url_ur']);
            }
        });
    }
};
