<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    	Schema::create('download', function (Blueprint $table) {

    		$table->increments('id');
    		$table->string('torrent_name');
    		$table->string('details');
    		$table->string('type');
    		$table->string('name');
    		$table->string('link');
    		$table->string('magnet');
    		$table->string('seeders');
    		$table->string('leechers');
    		$table->string('category_A');
    		$table->string('category_A_link');
    		$table->string('category_B');
    		$table->string('category_B_link');		
    		    		
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('download');
    }
}
