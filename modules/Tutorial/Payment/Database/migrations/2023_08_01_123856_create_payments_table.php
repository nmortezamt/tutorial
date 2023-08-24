<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tutorial\Payment\Models\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id');
            $table->foreignId('seller_id')->nullable();
            $table->foreignId('paymentable_id');
            $table->string('paymentable_type');
            $table->string('amount',10);
            $table->string('invoice_id');
            $table->string('gateway');
            $table->enum('status',Payment::$statuses);
            $table->tinyInteger('seller_p')->unsigned();
            $table->string('seller_share',10);
            $table->string('site_share',10);

            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
