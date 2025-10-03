<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('billing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rent_id')->nullable()->constrained('rent')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('billing_type'); // rent, utilities, cafe, maintenance, other
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('issued_date')->useCurrent();
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->decimal('late_fee', 8, 2)->default(0);
            $table->json('line_items')->nullable(); // For itemized billing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing');
    }
};