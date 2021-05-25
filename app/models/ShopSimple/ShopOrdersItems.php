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
    * Saves $_SESSION['cart_dimmm931_1604938863'] to DB (shop_order_item) if roreach loop. $_SESSION is in format [2 => 4, id =>quantity]
    * @param collection $savedID (saved order collection)
    * @param array $sessionData ($_SESSION['cart_dimmm931_1604938863'])
    * @param array $inCartItems (array containing DB results based on cart products' IDs)
    * @return boolean
    */
	public function saveFields_to_shop_order_item($savedID, $sessionData, $inCartItems) //args are [ id of saved Order in table {shop_orders_main}, Session cart $_SESSION['cart_dimmm931_1604938863'], SQl query of products in cart ]
    {		
		$v = array();
		foreach($sessionData as $key => $val){ //Mega Fix =>  you have to create new object in every step of loop, cant use $this
			
	        //FIX, should find by column iD 'shop_id'. Find the the index of current product ID in $inCartItems
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
		return true;
	}
	
	
   /**
    * HasOne Relation to show Product names from table {shop_simple}.
    * @param  
    * @return hasOne
    */
	public function productName2(){
		return $this->hasOne('App\models\ShopSimple\ShopSimple', 'shop_id', 'product_id')->withDefault(['shop_title' => 'Unknown']);      //$this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');}
    }
	
	

   /** 
    * BelongsTo Relation 
    * @param  
    * @return belongsTo
    */
	public function category()
    {
          return $this->belongsTo('App\models\ShopSimple\ShopOrdersMain', 'order_id', 'fk_order_id');

    }
		
	
   /** 
    * Method to delete delete relevant rows in table {order_item} when u delete something in table {shop_orders_main}
    * @param collection $collectionToDelete (collection of deleted items from table {shop_orders_main})
    * @return string $message
    */
	public function deleteRelevantItemsFrom_Order_item($collectionToDelete)
    {
        $array_of_ids = array();
        foreach($collectionToDelete as $v){
            array_push($array_of_ids, $v->order_id); //[id, id, id]            
        }
        $subDelete = ShopOrdersItems::whereIn('fk_order_id', $array_of_ids)->delete(); //delete multiple relevant ids
        if(!$subDelete){
            $message = '<i class="fa fa-check-square-o" style="font-size:24px"></i> No unpaid orders  older > 24 hours to delete.';   
        } else {
            $word = (count($collectionToDelete) > 1) ? 'orders' : 'order';
            $message = '<i class="fa fa-trash-o" style="font-size:24px"></i> ' . count($collectionToDelete) . ' old unpaid ' . $word  . ' older > 24 hours deleted';   
        } 
        return $message;        
    }	
	
}
