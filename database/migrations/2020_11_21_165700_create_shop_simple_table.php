<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSimpleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if (!Schema::hasTable('shop_simple')) { //my fix for migration
		    Schema::create('shop_simple', function (Blueprint $table) {
				
            $table->increments('shop_id');
			$table->string('shop_title', 82)->nullable();    //VARCHAR equivalent column, length 82 // ->nullable()  is a fix
			$table->string('shop_image', 222)->nullable();   //Эквивалент VARCHAR с длинной 222 // ->nullable()  is a fix
            $table->decimal('shop_price', 6, 2); // DECIMAL equivalent with a precision and scale //this means that your column is set up to store 6 places (scale), with 2 to the right of the decimal (precision). A sample would be 1234.56.
			$table->string('shop_currency', 8)->nullable();  //VARCHAR equivalent column, length 222 // ->nullable()  is a fix
			$table->text('shop_descr')->nullable();         //equivalent for text
			
			//Create Foreign key for table {shop_categories}	
			$table->unsignedInteger('shop_categ')->nullable();
            $table->foreign('shop_categ')->references('categ_id')->on('shop_categories')->onUpdate('cascade')->onDelete('cascade');
	        //End Create Foreign key for table {shop_categories}	
			
			$table->timestamp('shop_created_at')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных

			//$table->integer('wpBlog_author')->nullable();  //Эквивалент INTEGER для базы данных
			//$table->timestamp('wpBlog_created_at')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных
		    //$table->integer('wpBlog_category')->nullable();  //Эквивалент INTEGER для базы данных
			//$table->enum('wpBlog_status', ['0', '1'])->default('1'); //Эквивалент ENUM для базы данных
			
            });
		}
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_simple');
    }
}
