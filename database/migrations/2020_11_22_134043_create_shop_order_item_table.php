<?php
//Db table to store a one user's order split by items, ie Order contains 2 items (dvdx2, iphonex3). 
//This table Stores each separate line from order (i.e row1=> (1. dvd 2 price etc), row2=> (2. iphonex 3 price etc).
//general info about the order (general amount, price, email, etc ) is stored to table {shop_orders_main}
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (!Schema::hasTable('shop_order_item')) { //my fix for migration
          Schema::create('shop_order_item', function (Blueprint $table) {
            $table->increments('id');
			
            $table->unsignedInteger('fk_order_id')->nullable(); //from table {shop_orders_main}
			
			//Create Foreign key for table {shop_simple}	
			$table->unsignedInteger('product_id')->nullable()->comment = 'product Id from table shop_simple (ForeignKey)';   //product Id from table {shop_simple}
            $table->foreign('product_id')->references('shop_id')->on('shop_simple')->onUpdate('cascade')->onDelete('cascade');
	        //End Create Foreign key for table {shop_simple}
			
			$table->integer('items_quantity')->nullable()->comment = 'Quantity of this productd in order';
			$table->decimal('item_price', 6, 2); // DECIMAL equivalent with a precision and scale //this means that your column is set up to store 6 places (scale), with 2 to the right of the decimal (precision). A sample would be 1234.56.
            $table->string('currency', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //	
			$table->integer('user_buyer_id')->nullable()->comment = 'User who bought, if unlogged will be null';
			$table->string('uuid', 222)->nullable()->comment = 'Unique ID of order';  //Эквивалент VARCHAR с длинной 222 // 
			$table->timestamp('order_placed')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных

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
        Schema::dropIfExists('shop_order_item');
    }
}
