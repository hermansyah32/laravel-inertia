<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('parent_id')->nullable();
            $table->string('student_number')->nullable();
            $table->uuid('student_grade_id')->nullable();
            $table->uuid('student_class_id')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 16)->nullable();
            $table->text('photo_url')->nullable();
            $table->integer('cumulative_score')->default(0);
            $table->text('address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_profiles');
    }
};
