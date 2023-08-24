<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tutorial\Discount\Models\Discount;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('code')->unique()->nullable();
            $table->tinyInteger('percent')->unsigned();
            $table->enum('type',Discount::$types)->default(Discount::TYPE_ALL);
            $table->bigInteger('usage_limitation')->unsigned()->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->string('link')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('uses')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('discountables',function (Blueprint $table){
            $table->foreignId('discount_id');
            $table->foreignId('discountable_id');
            $table->string('discountable_type');
            $table->primary(['discount_id','discountable_id','discountable_type'],'discountable_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discountables');
    }
};
