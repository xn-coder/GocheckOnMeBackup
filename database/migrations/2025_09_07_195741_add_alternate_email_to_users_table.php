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
        Schema::table('users', function (Blueprint $table) {
            // Add alternate_email if it doesn't exist (checking spelling in original migration)
            if (!Schema::hasColumn('users', 'alternate_email')) {
                $table->string('alternate_email')->nullable()->after('alternate_phone_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'alternate_email')) {
                $table->dropColumn('alternate_email');
            }
        });
    }
};