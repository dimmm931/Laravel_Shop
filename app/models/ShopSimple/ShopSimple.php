<?php
//Model for ShopSimple DB that contains all products
namespace App\models\ShopSimple;

use Illuminate\Database\Eloquent\Model;

class ShopSimple extends Model
{
    /**
     * Order ID (random string).
     * @var string
     */
    private $UUID;

    /**
     * Connected DB table name.
     * @var string
     */
    protected $table = 'shop_simple';
  
    //protected $fillable = ['wpBlog_author', 'title', 'description', 'category_sel'];  
    public $timestamps    = false; //to override Error "Unknown Column 'updated_at'" that fires when saving new entry
    protected $primaryKey = 'shop_id'; // override
  
    
    /**
     * HasOne relation (for Category table)
     * @return hasOne
     *
     */
    public function categoryName(){
	    return $this->hasOne('App\models\ShopSimple\ShopCategories', 'categ_id', 'shop_categ')->withDefault(['name' => 'Unknown categoty']);      //$this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');}
    }


    
    /**
     * HasOne relation  (for Quantity table)
     * @return hasOne
     *
     */
    public function quantityGet(){
	    return $this->hasOne('App\models\ShopSimple\ShopQuantity', 'product_id', 'shop_id')->withDefault(['name' => 'Unknown quantity']);      //$this->belongsTo('App\modelName', 'foreign_key_that_table', 'parent_id_this_table');}
    }

  
  
    
    /**
     * Method to truncate/crop the text
     * @param string $text
     * @param int $maxLength
     * @return string
     *
     */
	public function truncateTextProcessor($text, $maxLength)
	{
        $length = $maxLength; 
		if(strlen($text) > $length){
		    $text = substr($text, 0, $length) . "...";
		} 
	    return $text;		
	}
	
	

   /**
    * Method to generate unique order number.
    * @param int $length
    * @return string
    */
	function generateUUID($length=10) 
	{
        $this->UUID = "sh-" . time() ."-". substr( md5(uniqid()), 0, $length);  //md5 the unique number
	    return $this->UUID;
    }
    
    
   /**
    * Method to get/select all products based on category (if any set) & price order (if any set)
    * @return collection
    *
    * 
    */
    function getProductsByCategory_and_PriceOrder()
    {
		//if no $_GET['shop-category'] - find all products with pagination. OR if $_GET but == empty
	    if (!isset($_GET['shop-category'])){ 
		    
			//found all products with pagination
			//$allDBProducts = ShopSimple::paginate(6); 
			//$allDBProducts = ShopSimple::orderBy('shop_price', 'desc')->paginate(6); //with pagination.
		
		    //Eloqent query with diffrent orderBy clauses based on $_GET['order']
			$allDBProducts = self::when(isset($_GET['order']) && $_GET['order'] == 'lowest', function ($q) {
               return $q->orderBy('shop_price', 'asc');
            })
			//case to order by highest price
			->when(isset($_GET['order']) && $_GET['order'] == 'highest', function ($q) {
               return $q->orderBy('shop_price', 'desc');
            })
			//case to order by newest inserted product
			->when(isset($_GET['order']) && $_GET['order'] == 'newest', function ($q) {
               return $q->orderBy('shop_created_at', 'desc');
            })
			
			//condition to use anyway (deploy pagination)
			->paginate(6); //with pagination

		}
		
		
		//if isset GET['shop-category'], find products by category with pagination
		if(isset($_GET['shop-category'])){
			
			//Eloqent query with diffrent orderBy clauses based on $_GET['order']
			$allDBProducts = self::when(isset($_GET['order']) && $_GET['order'] == 'lowest', function ($q) {
               return $q->orderBy('shop_price', 'asc');
            })
			//case to order by highest price
			->when(isset($_GET['order']) && $_GET['order'] == 'highest', function ($q) {
               return $q->orderBy('shop_price', 'desc');
            })
			//case to order by newest inserted product
			->when(isset($_GET['order']) && $_GET['order'] == 'newest', function ($q) {
               return $q->orderBy('shop_created_at', 'desc');
            })
			//condition to use anyway (find items by category)
			->where('shop_categ', $_GET['shop-category'])->paginate(4); //with pagination

		}
        return $allDBProducts;
    }
    
    
   /**
    * Method to count found products
    * @return collection
    *
    * 
    */
    function countProducts()
    {
        if (!isset($_GET['shop-category'])){ 
            $countProducts = self::all(); //for counting all products 
        } else {
            $countProducts = self::where('shop_categ', $_GET['shop-category'])->get();
        }
        return $countProducts;

    }
    
    
    /**
     * Method to read cart $_SESSION['cart_dimmm931_1604938863'], e.g [5,7,9] and find relevant products in DB
     * @return array $inCartItems
     *
     * 
     */
    function findCartProductsByID()
    {
        $arrayWithIDsInCart = array(); //array to store products IDs that are currentlyin cart, i.e [5,7,9]  
		foreach($_SESSION['cart_dimmm931_1604938863'] as $key => $value){
		    array_push($arrayWithIDsInCart, $key);
		}
		//find DB products, but only those ids are present in the cart, i.e $_SESSION['cart_dimmm931_1604938863']
		$allProductsAll = self::whereIn('shop_id', $arrayWithIDsInCart)->get();
        $inCartItems = $allProductsAll->toArray(); //object to array to perform search_array in view
        return $inCartItems;
    }
    
    
    /**
     * Method to add selected products to Cart $_SESSION['cart_dimmm931_1604938863'] or remove some product if quantity == 0
     * @param Request $request
     * @return Response
     *
     * 
     */
    function addOrRemoveItmemsFromCart($request)
    {
		$itemsQuantity = (int)$request->input('yourInputValue'); //gets quantity from form $_POST[]
		$productID     = (int)$request->input('productID');      //gets productID (hidden field) from form $_POST[]
		$productOne    = self::where('shop_id', $request->input('productID'))->get(); //get one selected product from SQL DB by id
		//$keyN = array_search($productID , array_keys($_SESSION['productCatalogue'])); //find in $_SESSION['productCatalogue'] index the product by id
		
        //Remove from Cart		
		if ($itemsQuantity == 0){
			if (isset($_SESSION['cart_dimmm931_1604938863']) && isset($_SESSION['cart_dimmm931_1604938863'][$productID]) ){//if Session is set and that productID is in it
				$temp = $_SESSION['cart_dimmm931_1604938863'];//save Session to temp var
				unset($temp[$productID]);
				$_SESSION['cart_dimmm931_1604938863'] = $temp;//write temp var to Cart
				return redirect('/shopSimple')->with('flashMessageFailX', 'Product <b> ' . $productOne[0]->shop_title . ' </b> was deleted from cart' );
			} else {}
            
        //Add to Cart
		} else {
            if (!isset($_SESSION['cart_dimmm931_1604938863'])) {//if Session['cart_dimmm931_1604938863'] does not exist yet
			    $temp = array();
                $temp[$productID] = $itemsQuantity;//add to array a quantity of products 
            } else {//if if Session['cart_dimmm931_1604938863'] already contains some products, ie. was prev added to cart
                $temp = $_SESSION['cart_dimmm931_1604938863'];//save Session to temp var
                if (!array_key_exists($productID, $temp)) {//check if Cart already contains this product
                    $temp[$productID] = $itemsQuantity; //add to array a quantity of products 
                } else { //if product was not prev selected (added to cart)
				    $temp[$productID] = $itemsQuantity;
			    }				
            }
		}
        $_SESSION['cart_dimmm931_1604938863'] = $temp;//write temp var to Cart
		
		return redirect('/shopSimple')->with('flashMessageX', "Item was successfully added to cart. Product: " . $productOne[0]->shop_title . ". Quantity : " . $request->input('yourInputValue') . " items" );
    } 
    
    /**
     * Method to save new product to DB (used in admin panel)
     * @param array $data
     * @param string $imageName
     * @return array
     *
     * 
     */
    function saveNewProduct($data, $imageName)
    {
        $this->shop_title      = $data['product-name'];
		$this->shop_descr      = $data['product-desr'];
		$this->shop_price      = $data['product-price'];
		$this->shop_image      = $imageName; //$request->image->getClientOriginalName();
		$this->shop_currency   = '$' ;
		$this->shop_categ      = $data['product-category'];
		$this->sh_device_type  = $data['product-type'];
		$this->shop_created_at = date('Y-m-d H:i:s');
        
        if($this->save()) {
            return array('status'=> true, 'savedID'=> $this->shop_id);
        } else {
            return array('status'=> false);

        }            
    }
    
}
