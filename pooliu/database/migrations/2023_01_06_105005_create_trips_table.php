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
        Schema::create('trips', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('location');
            $table->enum('campus', ['1', '2', '3', '4', '5', '6', '7', '8', '9']); 
            $table->date('date');
            $table->time('time');
            $table->enum('ride_type', ['1', '2', '3']); 
            $table->integer('seats');
            $table->boolean('is_going');
            $table->timestamps();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
