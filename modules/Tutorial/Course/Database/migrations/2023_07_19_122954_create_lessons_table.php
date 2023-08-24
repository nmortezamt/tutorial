<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tutorial\Course\Models\Lesson;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('media_id')->nullable();
            $table->unsignedBigInteger('season_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->boolean('is_free')->default(0);
            $table->text('body')->nullable();
            $table->tinyInteger('time')->unsigned();
            $table->integer('number')->unsigned();
            $table->enum('confirmation_status',Lesson::$confirmation_statuses)->default(Lesson::CONFIRMATION_STATUS_PENDING);
            $table->enum('status',Lesson::$statuses)->default(Lesson::STATUS_OPENED);

            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('SET NULL');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
