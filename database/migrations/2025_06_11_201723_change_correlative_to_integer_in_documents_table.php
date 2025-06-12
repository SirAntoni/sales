<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            Schema::table('documents', function (Blueprint $table) {
                // Cambiamos de VARCHAR/CHAR a INT UNSIGNED
                $table->unsignedInteger('correlative')->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            Schema::table('documents', function (Blueprint $table) {
                // Volver a VARCHAR (ajusta la longitud si hacía falta)
                $table->string('correlative', 10)->change();
            });
        });
    }
};
