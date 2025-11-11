<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('language')->default('es');
            $table->string('timezone')->default('America/Bogota');
            $table->string('currency')->default('COP');
            $table->string('date_format')->default('d/m/Y');
            $table->string('time_format')->default('24');
            $table->decimal('tax_percentage', 5, 2)->default(19.00);
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
        Schema::dropIfExists('system_preferences');
    }
}