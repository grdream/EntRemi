<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained()->cascadeOnDelete();
            $table->enum('pattern', [
                'daily',
                'weekly',
                'bi_weekly',
                'twice_per_week',
                'monthly',
                'irregular',
                'movie_one_time',
            ])->default('weekly');
            $table->json('days_of_week')->nullable(); // e.g. ["monday","friday"]
            $table->time('air_time');
            $table->string('timezone', 60)->default('UTC');
            $table->unsignedTinyInteger('episodes_per_slot')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
