<?php
// DB table {shop_orders_main} to store general info about the order (general amount, price, email, etc )
//While Db table {shop_order_item} is used to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
// then we store to table {shop_order_item} each separate line from order (i.e row1=> (1. dvd 2 price etc), row2=> (2. iphonex 3 price etc).
//

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrdersMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		//
		if (!Schema::hasTable('shop_orders_main')) { //my fix for migration
          Schema::create('shop_orders_main', function (Blueprint $table) {
            $table->increments('order_id');
			$table->string('ord_uuid', 222)->nullable()->comment = 'Unique ID';  //Эквивалент VARCHAR с длинной 222 // 
			$table->enum('ord_status', ['proceeded', 'not-proceeded', 'delivered'])->default('not-proceeded'); //Эквивалент ENUM для базы данных
			$table->decimal('ord_sum', 6, 2)->comment = 'General Order sum'; // DECIMAL equivalent with a precision and scale //this means that your column is set up to store 6 places (scale), with 2 to the right of the decimal (precision). A sample would be 1234.56.
            $table->integer('items_in_order')->nullable()->comment = 'Quantity of different products in order';;
			$table->timestamp('ord_placed')->default( date('Y-m-d H:i:s') ); //	Эквивалент TIMESTAMP для базы данных
            
			//Create Foreign key for table {users}	//user ID
			$table->unsignedInteger('ord_user_id')->nullable()->comment = 'User/Buyer Id, 0 if unlogged';  //userId of user who bought the order, if not logged id = 0
            $table->foreign('ord_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
	        //End Create Foreign key for table {users}
			
			
			//address
			$table->string('ord_name', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //
			$table->string('ord_address', 77)->nullable();  //Эквивалент VARCHAR с длинной 222 //	
            $table->string('ord_email', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //
			$table->string('ord_phone', 42)->nullable();  //Эквивалент VARCHAR с длинной 222 //
			
			//payment
			$table->enum('if_paid', ['0', '1', ])->default('0')->comment = 'If user paid the order'; //Эквивалент ENUM для базы данных
            $table->integer('payment_id')->nullable()->comment = 'If paid Paypal etc, payment ID';  //product Id from table {shop_simple}
			$table->timestamp('when_paid')->nullable()->comment = 'when user paid'; //	Эквивалент TIMESTAMP для базы данных

			//delivery type
            $table->enum('delivery', ['mail', 'self-take', ])->default('mail'); //Эквивалент ENUM для базы данных

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
        Schema::dropIfExists('shop_orders_main');
    }
}
