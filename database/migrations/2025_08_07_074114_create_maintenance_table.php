<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('issue_description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('category', ['plumbing', 'electrical', 'appliances', 'security', 'cleaning', 'hvac', 'other'])->default('other');
            $table->text('staff_notes')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->datetime('assigned_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->json('photos')->nullable(); // Store photo URLs
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
