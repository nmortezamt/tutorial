<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tutorial\User\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->nullable();
            $table->string('mobile')->nullable();
            $table->string('headLine')->nullable();
            $table->string('ip')->nullable();
            $table->string('telegram')->nullable();
            $table->string('card_number',16)->nullable();
            $table->string('shaba',24)->nullable();
            $table->bigInteger('balance')->default(0);
            $table->text('bio')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status',User::$statuses)->default('active');
            $table->string('password');
            $table->rememberToken();

            $table->foreign('image_id')->references('id')->on('media')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
