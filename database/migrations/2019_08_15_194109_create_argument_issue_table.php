<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArgumentIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('argument_issue', function (Blueprint $table) {
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
        Schema::table('argument_issue', function (Blueprint $table) {
            $table->dropForeign('argument_issue_argument_id_foreign');
            $table->dropForeign('argument_issue_issue_id_foreign');
        });

        Schema::dropIfExists('argument_issue');
    }
}
