<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('apartment_number')->unique();
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->decimal('price', 10, 2);
            $table->enum('apartment_type', ['studio', '1br', '2br', '3br', 'penthouse']);
            $table->text('description')->nullable();
            $table->integer('floor_number')->nullable();
            $table->decimal('size_sqm', 8, 2)->nullable();
            $table->json('amenities')->nullable(); // ['wifi', 'ac', 'parking', etc.]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
