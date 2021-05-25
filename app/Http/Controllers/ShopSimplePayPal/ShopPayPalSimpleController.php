<?php
//uses $_SESSION['cart_dimmm931_1604938863'] to store and retrieve user's cart. Format is { [8]=> int(3) [1]=> int(2) [4]=> int(1) }
//uses $_SESSION['orderID_1604938863'] to store user's Order ID (stores table 'shop_orders_main' increment ID, not UUID)
namespace App\Http\Controllers\ShopSimplePayPal;

use Illuminate\Http\Request;
use App\models\ShopSimple\ShopSimple;     //model for DB table 
use App\models\ShopSimple\ShopCategories; //model for DB table 
use Illuminate\Support\Facades\Validator;
use App\models\ShopSimple\ShopOrdersMain; //model for DB table {shop_orders_main} that stores general info about the order (general amount, price, email, etc )
use App\models\ShopSimple\ShopOrdersItems; //model for DB table {shop_order_item} to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShopShippingRequest; //my custom Form validation via Request
use App\ThirdParty_SDK\LiqPaySDK\LiqPay; //LiqPay SDK
use App\Http\Controllers\Controller;

class ShopPayPalSimpleController extends Controller
{
    public function __construct(){
		session_start();
	}
	
	
	/**
     * Displays shop start page
     * @return \Illuminate\Http\Response
     * 
     *
     */
    public function index()
    {
		//here goes session_start via construct;
		
		$model                = new ShopSimple();       //to call model method, e.g truncateTextProcessor($text, $maxLength)
		$allCategories        = ShopCategories::all();  //for <select> dropdown
		$allProductsSearchBar = ShopSimple::all();      // for Search Bar
		
        //getting/selecting all products based on category (if any set) & price order (if any set)
        $allDBProducts = $model->getProductsByCategory_and_PriceOrder();
        
        //Method to count found products
        $countProducts = $model->countProducts();
      		
		//adds this to SQL Result Object in order Laravel Pagination links would including other GET parameters when u naviagate to page=2, etc; i.e the URL would contain previous $_GET[] params, like it was "shopSimple?order=lowest", when goes to page=2 it will be "shopSimple?order=lowest&page=2". Without this fix URL will be just "shopSimple?page=2"
		$allDBProducts = $allDBProducts->appends(\Illuminate\Support\Facades\Input::except('page'));
	    
		return view('ShopPaypalSimple.shopIndex')->with(compact(
		                                          'allDBProducts', //with pagination
												  'countProducts',
			                                      'model',
			                                      'allCategories',
                                                  'allProductsSearchBar'
		));  
	}
	
	
	/**
     * Display Cart page
     * @return \Illuminate\Http\Response
     * 
     */
    public function cart()
    {
		//here goes session_start via construct;
		
		//if session with Cart set previously (user has already selected some products to cart)
		if(isset($_SESSION['cart_dimmm931_1604938863'])){
           //Read cart $_SESSION['cart_dimmm931_1604938863'], e.g [5,7,9] and find relevant products in DB
           $model = new ShopSimple();
           $inCartItems = $model->findCartProductsByID();
		   return view('ShopPaypalSimple.cart')->with(compact('inCartItems')); 
		
        //if session with Cart WAS NOT set previously, returns view only	
		} else {
			return view('ShopPaypalSimple.cart'); 
		}
	}
	
	
	
	
	/**
     * Display One Product as a result from search bar (or by click on direct <href> in pop-up modal)
     * @return \Illuminate\Http\Response
     * 
     */
    public function showOneProductt($id)
    {
		//here goes session_start via construct;
		
		 //additional check in case user directly intentionally navigates to this URL with not-existing ID
	    if (!ShopSimple::where('shop_id', $id)->exists()) { 
	        throw new \App\Exceptions\myException('Product ' . $id . ' does not exist');
	    }
		
		//find the product by id
		$productOne = ShopSimple::where('shop_id', $id)->get();
		$model      = new ShopSimple(); //to call model method, e.g truncateTextProcessor($text, $maxLength)
		return view('ShopPaypalSimple.showOneProduct')->with(compact('productOne', 'model')); 
	}
	
	
	
	
    /**
     * Method to add to cart. Request comes from form in ShopPaypalSimple.shopIndex
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
    public function storeToCart(Request $request)
    {
        //here goes session_start via construct;
        
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('productID')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
		
		$rules = [
			'yourInputValue' => ['required', 'integer',  ] , 
			'productID'      => [ 'required', 'integer' ] , 
			
		];
		
	    //creating custom error messages. Should pass it as 3rd param in Validator::make()
	    $mess = [ 
            'yourInputValue.required' => 'We need this field to be specified',
            'productID.required'      => 'We need this field to be specified'
        ];
		
	    $validator = Validator::make($request->all(),$rules, $mess);
	    if ($validator->fails()) {
			return redirect('/shopSimple')->withInput()->with('flashMessageFailX', 'Validation Failed' )->withErrors($validator);
	    }
		
        
        //Method to add selected products to Cart $_SESSION['cart_dimmm931_1604938863'] or remove some product if quantity == 0
        $model = new ShopSimple();
        return $model->addOrRemoveItmemsFromCart($request);
		
	}
	
	
		
	

	/**
     * Method to update cart items(if they were changed in cart) and go to check-out page. Handles $_POST request and redirects further. Fired by button "Check-out" in Cart.
     * Gets form data with Final Cart send via POST form and redirects to GET /checkOut2. Request comes from form in ShopPaypalSimple.cart
     * NEVER return a view in response to a POST request. Always redirect somewhere else which shows the result of the post or displays the next form.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function checkOut(Request $request)
    {        
        //here goes session_start via construct;
        
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('productID')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
		$productIDs   = $request->input('productID');       //comes as array [6,9,9]
		$productQuant = $request->input('yourInputValueX'); //comes as array [6,9,9]
		
		//check if inputs are not even
		if(count($productIDs) != count($productQuant)){
			throw new \App\Exceptions\myException('Cart inputs arrays ids and quantity are not even.');
		}
		
		//update the $_SESSION['cart_dimmm931_1604938863'] unless the case the user --minus product at cart till zero
		$temp = array();
		for ($i = 0; $i < count($productIDs); $i++){
		    if((int)$productQuant[$i] != 0){
			    $temp[$productIDs[$i]] = (int)$productQuant[$i];//add to array number of products 
		    }
		}
		$_SESSION['cart_dimmm931_1604938863'] = $temp;//write temp var to Cart
		
	    return redirect('/checkOut2');

	}
	
	

	
	/**
     * Check-out view page. $_GET Method is accessed via Shop TimeLine click or redirect from this controller function checkOut(Request $request)
	 * Displays page with Shipping details form
     * CheckOut == Order page
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
    public function checkOut2()
    {
		//here goes session_start via construct;
		
		//Generate UUID (unique ID for order)
		$model = new ShopSimple();
		$uuid  = $model->generateUUID(6);
		
		//Gets Products that are already in cart to display them in view
		//if session with Cart set previously (user has already selected some products to cart)
		if(isset($_SESSION['cart_dimmm931_1604938863'])){
            //Read cart $_SESSION['cart_dimmm931_1604938863'], e.g [5,7,9] and find relevant products in DB
            $model = new ShopSimple();
            $inCartItems = $model->findCartProductsByID();
		} 

        return view('ShopPaypalSimple.checkOut')->with(compact('inCartItems', 'uuid')); 

	}
	
	
	

	/**
     * Handles $_POST request and redirects further. NEVER return a view in response to a POST request. Always redirect somewhere else which shows the result of the post or displays the next form.
     * $_POST Method gets <form> data via $_POST from Checkout/Order page {i.e this controller function checkOut2()}(Shipping details (address, phone. etc)) and redirects to $_GET page route {payPage2}. 
	 * Form Request comes from form in ShopPaypalSimple.check-out
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
    public function pay1(ShopShippingRequest $request)  
    {
        //here goes session_start via construct;
        
		//if $_POST['u_name'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('u_name')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page. <p>Param is missing.</p>');
		}
		
		//gets all inputs
		$input = $request->all();
		
		//Gets Products that are already in cart to display them in view
		//if session with Cart set previously (user has already selected some products to cart)
		if(isset($_SESSION['cart_dimmm931_1604938863'])){
            //Read cart $_SESSION['cart_dimmm931_1604938863'], e.g [5,7,9] and find relevant products in DB. Returns array with DB results based on cart products' IDs
            $model = new ShopSimple();
            $inCartItems = $model->findCartProductsByID();
		} 
		//End Gets Products that are already in cart to display them in view  
				
		//save an Order to DB tables {shop_orders_main} and {shop_order_item}
		$shopOrdersMain  = new ShopOrdersMain();
		$ShopOrdersItems = new ShopOrdersItems();
		
		//additionally check if SESSION still exists
		if (!isset($_SESSION['cart_dimmm931_1604938863'])) {
		    return redirect('/checkOut2')->with('flashMessageFailX', "Error, SESSION is corrupted " );
		}
			
		if($savedID = $shopOrdersMain->saveFields_to_shopOrdersMain($request->all())){  //saving to table {shop_orders_main} DB that stores general info about the order (general amount, price, email, etc ) //$savedID is an id of saved/Inserted row
		    
			try { 
			    $ShopOrdersItems->saveFields_to_shop_order_item($savedID, $_SESSION['cart_dimmm931_1604938863'], $inCartItems );  // saving to table {shop_order_item} to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
			    
                //save to Session Order ID (i.e stores table 'shop_orders_main' increment ID, not UUID)
                $_SESSION['orderID_1604938863'] = $savedID;//write  var to Cart
                
                return redirect('payPage2')
                    ->with(compact('input', 'savedID'))
                    ->with('flashMessageX', "Your Order data is saved to DB with id " . $savedID . ". Now you have 24 hours to proceed with payment or the Order will be deleted." ); //$input in longer neccessary, reassigned to  $savedID , i.e ID of saved order (and use it to get values from DB)
		    
            //an attempt to delete $savedID if $ShopOrdersItems->saveFields_to_shop_order_item() fails
			} catch( Throwable $e ) {
				$delete = ShopOrdersMain::where('order_id', $savedID)->delete(); //If error Delete by ID from table {shop_orders_main} as well
				return redirect('/checkOut2')->with('flashMessageFailX', "Error saving to DB {shop_order_item}. Try Later" );
			}
			
		} else {
		    return redirect('/checkOut2')->with('flashMessageFailX', "Error saving to DB {shop_orders_main}. Try Later" );

		}
	}
	
	
	
	/**
     * $_GET Method is accessed via redirect from function pay1(Request $request) with data $input
     * @param  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function pay2()
    {
        //here goes session_start via construct;
        
        if(!isset($_SESSION['orderID_1604938863'])){ 
			return redirect('/checkOut2')->with('flashMessageFailX', '<i class="fa fa-angle-double-left" style="font-size:3em;color:red"></i> &nbsp; You have no order checked-out to proceed to payment. Do it at check-out page ' );
		}
        
		//Gets Products that are already in cart to display them in view
		//if session with Cart set previously (user has already selected some products to cart)
		if(isset($_SESSION['cart_dimmm931_1604938863'])){
            //Read cart $_SESSION['cart_dimmm931_1604938863'], e.g [5,7,9] and find relevant products in DB
            $model = new ShopSimple();
            $inCartItems = $model->findCartProductsByID();
		} 
		//End Gets Products that are already in cart to display them in view
		
		$thisOrderID = $_SESSION['orderID_1604938863'];  //session()->get('savedID'); //gets the ID of saved order (not UUID)
		
		//finding this One order in DB by ID {$savedID} passed from {function pay1}
		$thisOrder = ShopOrdersMain::where('order_id', $thisOrderID )->get();
		
		//LiqPay SDK Button (to pass to view). LiqPay Object is created here with credentials. method is called in view.    
		//$liqpay = new LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));
		$liqpay = new LiqPay(env('LIQPAY_PUBLIC_KEY', 'screw'), env('LIQPAY_PRIVATE_KEY', 'screw')); //using env Constants
       	
		return view('ShopPaypalSimple.pay-page')
            ->with(compact('inCartItems', 'thisOrder', 'thisOrderID', 'liqpay')); //'input', 
	}
	
	
	
	/**
     * Handles payment callback,
     * final payment page, returned by PayPal INP Listener/LiqPay callback, displays if payment was successfull or not
     * not 100% implemented due to lack of SSL
     * @return \Illuminate\Http\Response
     */
	 
    public function payOrFail()
    {
        //here goes session_start via construct;
        
        //detect if PayPal or LiqPay callback
        /*
         if (preg_match("/liqpay/i", $_SERVER['HTTP_REFERER'])){
             
        } else if (preg_match("/paypal/i", $_SERVER['HTTP_REFERER'])){
        }
        */
        
        //update status as PAID => shop_orders_main ->if_paid
        //update Payment Time   => shop_orders_main ->when_paid
        //clear session $_SESSION['cart_dimmm931_1604938863']
        //clear session $_SESSION['orderID_1604938863']
        
		$postData = file_get_contents('php://input');
		$input_data = $_POST;
        
		return view('ShopPaypalSimple.payOrFail_final')->with(compact('postData', 'input_data'));  
	}

}