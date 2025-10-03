<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('monthly_rent', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
            $table->text('terms')->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent');
    }
};
