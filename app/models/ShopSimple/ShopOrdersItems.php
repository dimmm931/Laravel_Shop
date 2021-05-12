<?php
//Model for {shop_order_item} DB to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
//While Db table {shop_orders_main} is used  stores general info about the order (general amount, price, email, etc )

namespace App\models\ShopSimple;

use Illuminate\Database\Eloquent\Model;
use App\models\ShopSimple\ShopOrdersItems; //model for DB table {shop_order_item} to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 


class ShopOrdersItems extends Model
{

  /**
   * Connected DB table name.
   *
   * @var string
   */
  protected $table = 'shop_order_item';
  
  public $timestamps = false; //put in model to override Error "Unknown Column 'updated_at'" that fires when saving new entry
   protected $primaryKey = 'id'; // override
  
  
  
  
  
  
  
    /**
    * saves $_SESSION['cart_dimmm931_1604938863'] to DB (shop_order_item) if roreach loop. $_SESSION is in format [2 => 4, id =>quantity]
    *
    * @param  
    * @return 
    */
	public function saveFields_to_shop_order_item($savedID, $sessionData, $inCartItems){ //args are [ id of saved Order in table {shop_orders_main}, Session cart $_SESSION['cart_dimmm931_1604938863'], SQl query of products in cart ]
		
		//try{
		    $v = array();
		    foreach($sessionData as $key => $val){ //Mega Fix =>  you have to create new object in every step of loop, cant use $this
			
			  //MEGA FIX, should find by column iD 'shop_id'. Find the the index of current product ID in $inCartItems
		      $keyN = array_search($key, array_column($inCartItems, 'shop_id')); //returns 3
			
 			  $new = new self();      //Mega Fix =>  you have to create new object in every step of loop, cant use $this
			  $new->fk_order_id =     $savedID; //id of saved Order in table {shop_orders_main}
			  $new->product_id =      $key;
			  $new->items_quantity =  $val;
			  $new->item_price =      $inCartItems[$keyN]['shop_price'];
			  $new->currency =        $inCartItems[$keyN]['shop_currency'];
			  //$new ->ord_user_id =     auth()->user()->id ?  auth()->user()->id : 0;// User/Buyer Id, 0 if unlogged
			  $new ->save();
              			  
		}
		
		
		//} catch(Illuminate\Database\QueryException $ex /* Throwable $e */) {
                //DOES NOT WORK!!!!!!!!!!!!!
				//delete the Order inserted to table {shop_orders_main} by {$savedID}. I.e simulation of transactions
				/*dd('stopped');
				} catch (PDOException $ex) {
                  dd($ex);
				}
		*/	
				//ShopOrdersMain::where('order_id', $savedID)->delete(); //Delete
		//}
		   
		return true;
		
		
	}
	
	
   /**
    * HasOne Relationshio to show Product names from table {shop_simple}. Works!!!!!
    * @param  
    * @return 
    */
	public function productName2(){
		return $this->hasOne('App\models\ShopSimple\ShopSimple', 'shop_id', 'product_id')->withDefault(['shop_title' => 'Unknown']);      //$this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');}
    }
	
	

   /** 
    * HasMany //Doesnot work
    * @param  
    * @return 
    */
	public function category()
        {
          return $this->belongsTo('App\models\ShopSimple\ShopOrdersMain', 'order_id', 'fk_order_id');
		  //return $this->belongsTo('App\models\ShopSimple\ShopOrdersMain', 'order_id', 'fk_order_id');

        }
		
		
	
}
