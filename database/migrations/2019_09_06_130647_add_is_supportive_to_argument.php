<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSupportiveToArgument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('argument_fact', function (Blueprint $table) {
            $table->boolean('is_supportive')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('argument_fact', function (Blueprint $table) {
            //
        });
    }
}
