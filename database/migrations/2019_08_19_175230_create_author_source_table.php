<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_source', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('source_id');
            $table->timestamps();

            $table->foreign('author_id')
                  ->references('id')
                  ->on('authors')
                  ->onDelete('cascade');

            $table->foreign('source_id')
                  ->references('id')
                  ->on('sources')
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
        Schema::table('author_source', function (Blueprint $table) {
            $table->dropForeign('author_source_author_id_foreign');
            $table->dropForeign('author_source_source_id_foreign');
        });

        Schema::dropIfExists('author_source');
    }
}
