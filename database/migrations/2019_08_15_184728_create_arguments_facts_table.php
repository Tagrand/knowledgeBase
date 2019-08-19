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
        Schema::create('arguments_facts', function (Blueprint $table) {
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

        Schema::table('arguments_facts', function (Blueprint $table) {
            $table->dropForeign('arguments_facts_argument_id_foreign');
            $table->dropForeign('arguments_facts_fact_id_foreign');
        });
         
        Schema::dropIfExists('arguments_facts');
    }
}
