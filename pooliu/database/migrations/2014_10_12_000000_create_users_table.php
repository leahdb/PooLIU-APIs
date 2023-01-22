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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('LIU_ID')->unique();
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->integer('verification_num')->nullable()->default(null);
            $table->boolean('verification_status')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('phone_num')->nullable()->default(null);
            $table->binary('profile_pic')->nullable()->default(null);
            $table->boolean('is_LIU');
            $table->boolean('gender')->nullable()->default(null);
            $table->integer('score')->nullable()->default(5);
            $table->string('password');
            $table->boolean('is_driver')->default(0);
            $table->boolean('is_rider')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
