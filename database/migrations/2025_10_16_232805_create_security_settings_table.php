<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuritySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('min_password_length')->default(8);
            $table->boolean('require_numbers')->default(true);
            $table->boolean('require_symbols')->default(false);
            $table->boolean('allow_password_change')->default(true);
            $table->boolean('two_factor_auth')->default(false);
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration')->default(30); // minutos
            $table->boolean('log_activity')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_settings');
    }
}