<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixForeignKeyConstraintsForRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eliminar las restricciones de clave foránea existentes
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
        });

        Schema::table('facility_room', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
        });

        // Recrear las restricciones con onDelete('cascade')
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
        });

        Schema::table('facility_room', function (Blueprint $table) {
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar las restricciones con cascade
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
        });

        Schema::table('facility_room', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
        });

        // Recrear las restricciones originales sin cascade
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms');
        });

        Schema::table('facility_room', function (Blueprint $table) {
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms');
        });
    }
}