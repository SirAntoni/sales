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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');
            $table->string('serie');
            $table->string('correlative');
            $table->date('date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('currency');
            $table->string('payment_method');
            $table->double('subtotal');
            $table->double('tax');
            $table->double('total');
            $table->string('xml_path');
            $table->string('cdr_path');
            $table->enum('status_sunat', ['ACEPTADO', 'RECHAZADO']);
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
