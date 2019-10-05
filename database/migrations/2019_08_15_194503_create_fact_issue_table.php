<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_issue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fact_id');
            $table->unsignedBigInteger('issue_id');
            $table->timestamps();

            $table->unique(['fact_id', 'issue_id']);

            $table->foreign('issue_id')
                  ->references('id')
                  ->on('issues')
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
        Schema::table('fact_issue', function (Blueprint $table) {
            $table->dropForeign('fact_issue_issue_id_foreign');
            $table->dropForeign('fact_issue_fact_id_foreign');
        });

        Schema::dropIfExists('fact_issue');
    }
}
