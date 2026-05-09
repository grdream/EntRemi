<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('host');
            $table->unsignedSmallInteger('port')->default(587);
            $table->string('username');
            $table->text('password'); // stored encrypted via Crypt::encrypt()
            $table->enum('encryption', ['tls', 'ssl', 'none'])->default('tls');
            $table->string('from_address');
            $table->string('from_name');
            $table->boolean('is_active')->default(true);
            $table->dateTime('tested_at')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // one SMTP setting per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_smtp_settings');
    }
};
