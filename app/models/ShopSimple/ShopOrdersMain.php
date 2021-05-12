<?php
//Model for {shop_orders_main} DB that stores general info about the order (general amount, price, email, etc )
//While Db table {shop_order_item} is used to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
namespace App\models\ShopSimple;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShopOrdersMain extends Model
{

    /**
     * Connected DB table name.
     * @var string
     * 
     */
    protected $table      = 'shop_orders_main';
    public $timestamps    = false; //put in model to override Error "Unknown Column 'updated_at'" that fires when saving new entry
    protected $primaryKey = 'order_id'; // override
  
  
   
   /**
    * saves form inputs to DB (shop_orders_main)
    * @param $data
    * @return integer $id
    * 
    */
	public function saveFields_to_shopOrdersMain($data){
		
		$this->ord_uuid       = $data['u_uuid']; //auth()->user()->id;
		$this->ord_sum        = $data['u_sum'];
		$this->items_in_order = $data['u_items_in_order'];
		$this->ord_name       = $data['u_name'];
		$this->ord_address    = $data['u_address'];
		$this->ord_email      = $data['u_email'];
		$this->ord_phone      = $data['u_phone'];
		$this->ord_placed     = date('Y-m-d H:i:s');
		if(Auth::check()){
			$this->ord_user_id =   auth()->user()->id;
		}
		//$this->ord_user_id =     (auth()->user()->id) ?  auth()->user()->id : null ;// User/Buyer Id, 0 if unlogged
		
		$this->save();
		return $this->order_id; // returns the id of INSERTED row (this new created row). BUT SQL id column is "order_id"
	}
	
	
	
    /**
     * HasMany relation
     * @return HasMany
     *
     */
	 public function orderDetail()
        {
          //return $this->hasMany('App\models\ShopSimple\ShopOrdersItems', 'order_id','order_id');
		  //return $this->hasMany('App\models\ShopSimple\ShopOrdersItems', 'fk_order_id','order_id'); //->withDefault(['fk_order_id' => 'Unknown']);  //return $this->belongsTo('App\modelName', 'parent_id_this_table', 'foreign_key_that_table');
          //return $this->hasOne('App\models\ShopSimple\ShopOrdersItems', 'fk_order_id', 'order_id' );
		  return $this->hasMany('App\models\ShopSimple\ShopOrdersItems', 'fk_order_id', 'order_id');//->withDefault(['fk_order_id' => 'Unknown']);
        }
}
