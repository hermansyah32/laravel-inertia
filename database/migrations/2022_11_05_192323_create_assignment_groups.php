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
        Schema::create('assignment_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('author_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('subject_group_id')->nullable();
            $table->uuid('subject_content_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type');
            $table->dateTime('due_datetime')->nullable();
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
        Schema::dropIfExists('assignment_groups');
    }
};
