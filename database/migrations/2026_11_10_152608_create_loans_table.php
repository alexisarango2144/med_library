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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('book_id')
                ->constrained('books')
                ->restrictOnDelete();
            $table->timestamp('borrowed_at')->useCurrent();
            $table->date('estimated_return_date');
            $table->date('return_date')->nullable();
            $table->string('status', 20)->default('active');
           
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE loans
                ADD CONSTRAINT chk_return_date 
                    CHECK (return_date >= borrowed_at::date),
                ADD CONSTRAINT chk_estimated_date 
                    CHECK (estimated_return_date >= borrowed_at::date),
                ADD CONSTRAINT chk_status_values 
                    CHECK (status IN ('active', 'returned', 'overdue', 'lost'));
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
