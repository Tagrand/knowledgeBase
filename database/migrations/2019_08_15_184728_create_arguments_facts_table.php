<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArgumentsFactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('argument_fact', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('argument_id');
            $table->unsignedBigInteger('fact_id');
            $table->timestamps();

            $table->foreign('argument_id')
                  ->references('id')
                  ->on('arguments')
                  ->onDelete('cascade');

            $table->foreign('fact_id')
                  ->references('id')
                  ->on('facts')
                  ->onDelete('cascade');
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
            $table->dropForeign('argument_fact_argument_id_foreign');
            $table->dropForeign('argument_fact_fact_id_foreign');
        });

        Schema::dropIfExists('argument_fact');
    }
}
