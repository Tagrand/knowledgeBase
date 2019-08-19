<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facts_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fact_id')->unsigned();
            $table->integer('issue_id')->unsigned();
            $table->timestamps();

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
        Schema::table('facts_issues', function (Blueprint $table) {
            $table->dropForeign('facts_issues_issue_id_foreign');
            $table->dropForeign('facts_issues_fact_id_foreign');
        });

        Schema::dropIfExists('facts_issues');
    }
}
