<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            'site_name'           => 'EntRemi',
            'premium_upgrade_url' => 'https://wa.me/923274990424?text=I%20want%20to%20upgrade%20to%20EntRemi%20Premium',
            'system_mail_host'    => '',
            'system_mail_port'    => '587',
            'system_mail_user'    => '',
            'system_mail_pass'    => '',
            'system_mail_enc'     => 'tls',
            'system_mail_from'    => '',
            'system_mail_name'    => 'EntRemi',
            'system_sms_url'      => '',
            'system_sms_params'   => '',
            'system_sms_method'   => 'POST',
        ];

        foreach ($defaults as $key => $value) {
            \DB::table('system_settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
