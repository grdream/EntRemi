<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sms_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gateway_url');
            $table->text('api_key')->nullable();      // stored encrypted
            $table->string('sender_id')->nullable();
            $table->json('extra_params')->nullable();  // for ViserLab SMSLab custom params
            $table->boolean('is_active')->default(true);
            $table->dateTime('tested_at')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // one SMS setting per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sms_settings');
    }
};
