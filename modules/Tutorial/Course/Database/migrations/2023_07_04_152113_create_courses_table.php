<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tutorial\Course\Models\Course;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('banner_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('price',10);
            $table->string('teacher_percent',5);
            $table->float('priority')->nullable();
            $table->enum('type',Course::$types);
            $table->enum('status',Course::$statuses);
            $table->enum('confirmation_status',Course::$confirmation_statuses)->default(Course::CONFIRMATION_STATUS_PENDING);
            $table->text('body')->nullable();

            $table->foreign('teacher_id')->references('id')->on('users')->cascadeOnDelete();

            $table->foreign('banner_id')->references('id')->on('media')->onDelete('SET NULL');


            $table->foreign('category_id')->references('id')->on('categories')
            ->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
