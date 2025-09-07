<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->tinyInteger('status')->default(1);
            $table->string('phone')->nullable();
            $table->string('cell_phone_no')->nullable();
            $table->string('record_voice')->nullable();
            $table->string('alernate_email')->nullable();
            $table->string('alternate_phone_no')->nullable();
            $table->string('image')->nullable();
            $table->string('use_image')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->timestamp('subscription_start_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};