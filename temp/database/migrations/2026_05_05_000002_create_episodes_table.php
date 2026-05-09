<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('season_no')->nullable();
            $table->unsignedSmallInteger('episode_no');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('air_datetime');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('youtube_link')->nullable();
            $table->boolean('is_aired')->default(false);
            $table->boolean('notified')->default(false);
            $table->timestamps();

            // Composite index for the reminder scheduler query
            $table->index(['show_id', 'is_aired', 'notified', 'air_datetime'], 'idx_episodes_scheduler');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
