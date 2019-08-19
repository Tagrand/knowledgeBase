<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArgumentsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arguments_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('argument_id');
            $table->unsignedBigInteger('issue_id');
            $table->timestamps();

            $table->foreign('argument_id')
                  ->references('id')
                  ->on('arguments')
                  ->onDelete('cascade');

            $table->foreign('issue_id')
                  ->references('id')
                  ->on('issues')
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
        Schema::table('arguments_issues', function (Blueprint $table) {
            $table->dropForeign('arguments_issues_argument_id_foreign');
            $table->dropForeign('arguments_issues_issue_id_foreign');
        });
        
        Schema::dropIfExists('arguments_issues');
    }
}
