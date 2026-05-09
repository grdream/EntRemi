<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->foreignId('episode_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('remind_before_minutes')->default(30); // 30, 60, 1440, etc.
            $table->json('channels'); // ["email","sms"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'show_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
