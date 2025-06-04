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
            // 1) Agrego status (string) justo después de la PK
            $table->string('status')->default('pendiente')->after('id');

            // 2) Cambio status_sunat de enum a string (nullable)
            $table->string('status_sunat')->nullable()->change();

            // 3) Elimino la columna code
            if (Schema::hasColumn('documents', 'code')) {
                $table->dropColumn('code');
            }

            // 4) Agrego columnas para anulación
            $table->string('cdr_path_anulled')->nullable()->after('cdr_path');
            $table->string('xml_path_anulled')->nullable()->after('xml_path');
            $table->string('pdf_path_anulled')->nullable()->after('pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1) Revertir status_sunat a enum (ajusta los valores a los que originalmente tenía)
            $table->enum('status_sunat', ['ACEPTADO', 'RECHAZADO', 'PENDIENTE'])
                ->default('PENDIENTE')
                ->change();

            // 2) Volver a agregar code (si antes era string)
            $table->string('code')->nullable()->after('status_sunat');

            // 3) Eliminar status
            if (Schema::hasColumn('documents', 'status')) {
                $table->dropColumn('status');
            }

            // 4) Eliminar columnas de anulación
            $table->dropColumn('cdr_path_anulled');
            $table->dropColumn('xml_path_anulled');
            $table->dropColumn('pdf_path_anulled');
        });
    }
};
