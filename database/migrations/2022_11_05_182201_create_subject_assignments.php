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
        Schema::create('subject_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('author_id')->nullable();
            $table->uuid('assignment_group_id');
            $table->string('type');
            $table->json('question');
            $table->json('options')->nullable();
            $table->string('answer');
            $table->integer('score');
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
        Schema::dropIfExists('subject_assignments');
    }
};
