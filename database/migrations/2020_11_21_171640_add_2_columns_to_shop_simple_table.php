<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add2ColumnsToShopSimpleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::table('shop_simple', function($table) {
        $table->integer('sh_stock_quantity')->nullable(); //amount of product in stock // NOT USED, reassigned to separate table {shop_quantity_}
		$table->string('sh_device_type', 77)->nullable(); //product device type, i.e flash drive, turntable, etc
		
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    Schema::table('shop_simple', function($table) {
        $table->dropColumn('sh_stock_quantity'); // NOT USED, reassigned to separate table {shop_quantity}
		$table->dropColumn('sh_device_type');
    });
    }
}
