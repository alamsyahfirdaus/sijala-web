<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('a_surveys', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained('a_users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('a_survey_categories')
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->text('description');

            $table->string('photo')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->text('address')->nullable();

            $table->string('qr_code', 100)->nullable();

            $table->enum('status', ['draft', 'selesai'])
                ->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_surveys');
    }
};
