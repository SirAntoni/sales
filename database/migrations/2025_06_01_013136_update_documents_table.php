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
            if (Schema::hasColumn('documents', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }

            if (! Schema::hasColumn('documents', 'code')) {
                $table->string('code')->after('status_sunat');
            }

            if (! Schema::hasColumn('documents', 'notes')) {
                $table->json('notes')->nullable()->after('code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            if (! Schema::hasColumn('documents', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('notes');
            }

            if (Schema::hasColumn('documents', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('documents', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
