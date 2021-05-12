<?php
//DB table is used to store quantity of products from table {shop_simple}. Uses Foreign key
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopQuantityTable extends Migration
{
	
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shop_quantity')) { //my fix for migration
          Schema::create('shop_quantity', function (Blueprint $table) {
            $table->increments('id');
			
			//Product ID (FKey)
			//Create Foreign key for table {shop_simple}	
			$table->unsignedInteger('product_id')->nullable()->comment = 'product Id from table shop_simple (ForeignKey)';   //product Id from table {shop_simple}
            $table->foreign('product_id')->references('shop_id')->on('shop_simple')->onUpdate('cascade')->onDelete('cascade');
	        //End Create Foreign key for table {shop_simple}
			
			//Quantity balance of this productd in stock, will be ++ when u add a new amount arrived (added by admin)
			$table->integer('all_quantity')->nullable()->comment = 'All Quantity balance of this product in stock (will be ++)';
            
			//Quantity of this productd that left (will be -- when someones buy an item)
			$table->integer('left_quantity')->nullable()->comment = 'Quantity of this productd that left (will be --)';
			
			
			//Date of last updating of column 'all_quantity', updated when u add a new amount arrived (added by admin)
			$table->timestamp('all_updated')->default( date('Y-m-d H:i:s') )->comment = 'when new amount arrived'; //Эквивалент TIMESTAMP для базы данных
			
			//Date of last updating of column 'all_quantity', updated when when someones buy an item
			$table->timestamp('left_updated')->nullable()->comment = 'updated when when someones buy an item'; //	Эквивалент TIMESTAMP для базы данных
			
			
			
			
			/*
			$table->unsignedInteger('fk_order_id')->nullable(); //from table {shop_orders_main}
			$table->integer('items_quantity')->nullable()->comment = 'Quantity of this productd in order';
			$table->decimal('item_price', 6, 2); // DECIMAL equivalent with a precision and scale //this means that your column is set up to store 6 places (scale), with 2 to the right of the decimal (precision). A sample would be 1234.56.
            $table->string('currency', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //	
			$table->integer('user_buyer_id')->nullable()->comment = 'User who bought, if unlogged will be null';
			$table->string('uuid', 222)->nullable()->comment = 'Unique ID of order';  //Эквивалент VARCHAR с длинной 222 // 
			$table->timestamp('order_placed')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных
            */
            //$table->string('email')->unique();
          
          });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_quantity');
    }
}
