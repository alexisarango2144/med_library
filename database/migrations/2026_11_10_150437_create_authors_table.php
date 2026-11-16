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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->date('birthdate')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->text('biography')->nullable();
            $table->timestamps();
        });

        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        DB::statement("CREATE INDEX authors_full_search_trgm_idx ON authors USING GIN ((COALESCE(name, '') || ' ' || COALESCE(last_name, '') || ' ' || COALESCE(nationality, '')) gin_trgm_ops)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
