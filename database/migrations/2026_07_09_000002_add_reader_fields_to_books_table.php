<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            if (! Schema::hasColumn('books', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('download_allowed');
            }

            if (! Schema::hasColumn('books', 'pdf_source')) {
                $table->string('pdf_source', 40)->default('storage')->after('pdf_file_ur');
            }

            if (! Schema::hasColumn('books', 'drive_file_id')) {
                $table->string('drive_file_id')->nullable()->after('pdf_source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table): void {
            foreach (['drive_file_id', 'pdf_source', 'views_count'] as $column) {
                if (Schema::hasColumn('books', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
