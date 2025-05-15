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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->double('subtotal');
            $table->double('tax');
            $table->double('total');
            $table->boolean('status');
            $table->boolean('delivery');
            $table->double('delivery_fee');

            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();

            // Campos de facturación electrónica
            $table->string('serie', 10);
            $table->string('number', 20);
            $table->string('xml_path');
            $table->string('cdr_path');
            $table->enum('status_sunat', ['ACEPTADO', 'RECHAZADO']);

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
