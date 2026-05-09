<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['drama', 'movie', 'anime', 'tv_series', 'anime_movie', 'other'])->default('tv_series');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('backdrop_url')->nullable();
            $table->string('tmdb_id')->nullable()->index();
            $table->string('jikan_id')->nullable()->index();
            $table->string('imdb_id')->nullable();
            $table->enum('status', ['watching', 'completed', 'on_hold', 'dropped', 'plan_to_watch'])->default('plan_to_watch');
            $table->string('country', 80)->nullable();
            $table->string('language', 80)->nullable();
            $table->unsignedSmallInteger('total_episodes')->nullable();
            $table->json('genres')->nullable();
            $table->string('rating', 10)->nullable();
            $table->string('year', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
