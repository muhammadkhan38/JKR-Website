<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_progress', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_page')->default(1);
            $table->unsignedInteger('total_pages')->nullable();
            $table->timestamps();
            $table->index(['book_id', 'user_id']);
            $table->index(['book_id', 'session_id']);
        });

        Schema::create('reader_bookmarks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('page_number');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['book_id', 'user_id']);
            $table->index(['book_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reader_bookmarks');
        Schema::dropIfExists('reading_progress');
    }
};
