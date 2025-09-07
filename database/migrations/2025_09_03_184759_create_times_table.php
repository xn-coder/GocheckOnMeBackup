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
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('day', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']);
            $table->time('time1')->nullable();
            $table->time('time2')->nullable();
            $table->time('time3')->nullable();
            $table->enum('am_pm1', ['am', 'pm'])->nullable();
            $table->enum('am_pm2', ['am', 'pm'])->nullable();
            $table->enum('am_pm3', ['am', 'pm'])->nullable();
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
        Schema::dropIfExists('times');
    }
};
