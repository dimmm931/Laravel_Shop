<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (!Schema::hasTable('shop_transactions')) { //my fix for migration
          Schema::create('shop_transactions', function (Blueprint $table) {
            $table->increments('id');
			$table->string('type', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //
			$table->decimal('gross', 6, 2)->comment = 'Total sum of order/transaction'; // DECIMAL equivalent with a precision and scale //this means that your column is set up to store 6 places (scale), with 2 to the right of the decimal (precision). A sample would be 1234.56.
            $table->string('currency', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //			
			$table->integer('quantity')->nullable();  //
            $table->timestamp('order_placed')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных
            $table->string('status', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //
			
			//Create Foreign key for table {shop_orders_main}	
			$table->unsignedInteger('orderId')->nullable()->comment = 'Order Id from table {shop_orders_main} (ForeihnKey)';  //order Id from table {shop_orders_main}
            $table->foreign('orderId')->references('order_id')->on('shop_orders_main')->onUpdate('cascade')->onDelete('cascade');
	        //End Create Foreign key for table {shop_orders_main}
			
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
        Schema::dropIfExists('shop_transactions');
    }
}
