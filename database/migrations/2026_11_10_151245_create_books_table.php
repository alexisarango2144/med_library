<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->index();
            $table->string('slug', 255)->unique();
            $table->string('isbn', 13)->unique();
            $table->unsignedSmallInteger('publication_year')->nullable()->index();
            $table->unsignedInteger('page_count')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('available_stock')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE books ADD CONSTRAINT chk_books_stock CHECK (stock >= 0)');
        DB::statement('ALTER TABLE books ADD CONSTRAINT chk_books_available_stock CHECK (available_stock >= 0 AND available_stock <= stock)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
