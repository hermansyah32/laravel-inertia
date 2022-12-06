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
        Schema::create('user_has_profiles', function (Blueprint $table) {
            $table->uuid('user_id'); 
            $table->foreign('user_id')
                ->references('id') // users id
                ->on('users')
                ->onDelete('cascade');
            $table->string('profile_type');
            $table->uuid('profile_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_has_profiles');
    }
};
