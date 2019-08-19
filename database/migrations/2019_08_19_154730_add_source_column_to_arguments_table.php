<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceColumnToArgumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arguments', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id')->after('reason')->nullable();

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
        Schema::table('arguments', function (Blueprint $table) {
            $table->dropForeign('arguments_source_id_foreign');
            $table->dropColumn('source_id');
        });
        
    }
}
