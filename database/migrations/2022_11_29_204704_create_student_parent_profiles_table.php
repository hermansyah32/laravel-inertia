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
        Schema::create('student_parent_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('birthday')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 16)->nullable();
            $table->text('photo_url')->nullable();
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
        Schema::dropIfExists('student_parent_profiles');
    }
};
