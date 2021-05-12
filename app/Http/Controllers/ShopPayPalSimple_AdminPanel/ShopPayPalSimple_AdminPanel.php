<?php

namespace App\Http\Controllers\ShopPayPalSimple_AdminPanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\ShopSimple\ShopOrdersMain;  //model for DB table {shop_orders_main} that stores general info about the order (general amount, price, email, etc )
use App\models\ShopSimple\ShopOrdersItems; //model for DB table {shop_order_item} to store a one user's order split by items, ie if Order contains 2 items (dvdx2, iphonex3). 
use App\models\ShopSimple\ShopSimple;      //model for DB table 
use App\models\ShopSimple\ShopCategories;  //model for DB table 
use App\models\ShopSimple\ShopQuantity;    //model for DB table with product quantity
use Illuminate\Support\Facades\DB; 
use App\Http\Requests\ShopPaypalSimple_AdminPanel\OrderStatusChangeRequest; //my custom Form validation via Request Class (to update status in table {shop_orders_main})
use App\Http\Requests\ShopPaypalSimple_AdminPanel\SaveNewProductRequest; //my custom Form validation via Request Class (to create a new product in table {shop_simple})
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ShopPayPalSimple_AdminPanel extends Controller
{
    
	public function __construct(){
		$this->middleware('auth');
		/*if (!Auth::check()){
			throw new \App\Exceptions\myException('You are not logged');
		} */
		
	}
	
	
   /**
    * display Admin Panel start page
    * @return \Illuminate\Http\Response
    *
    * 
    */
    public function index()
    {
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		return view('ShopPaypalSimple_AdminPanel.adminPanelMain'); 
	}
	


    //==================================  Orders view section =================================================	
    
    /**
     * Displays Admin Panel Orders page
     * @return \Illuminate\Http\Response
     *
     * 
     */
    public function orders()
    {
		//RBAC control
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
	
		
		//if no $_GET['admOrderStatus'] - find all orders with {'ord_status', 'not-proceeded'} with pagination
	    if(!isset($_GET['admOrderStatus'])){ 
		    //Find all orders by where clause. Will engage hasMany in view
		    $shop_orders_main = ShopOrdersMain::where('ord_status', 'not-proceeded')->orderBy('order_id', 'desc')->paginate(3);
			
		    //count all orders with {'ord_status', 'not-proceeded'}
			$countOrders =  ShopOrdersMain::where('ord_status', 'not-proceeded')->get();    //for counting 
		}
		
		
		//if isset GET['admOrderStatus'], find products by GET['admOrderStatus'] with pagination
		if(isset($_GET['admOrderStatus'])){
            //Find all orders by where clause. Will engage hasMany in view
		    $shop_orders_main = ShopOrdersMain::where('ord_status', $_GET['admOrderStatus'])->orderBy('order_id', 'desc')->paginate(3);
			
		    //count all orders with {'ord_status', 'not-proceeded'}
			$countOrders =  ShopOrdersMain::where('ord_status', $_GET['admOrderStatus'])->get();    //for counting 
		}
		
		//count separatedly proceeded, not-proceeded, delivered 
		$countProceeded    = ShopOrdersMain::where('ord_status', 'proceeded')->count();     //for counting 
		$countNotProceeded = ShopOrdersMain::where('ord_status', 'not-proceeded')->count(); //for counting 
		$countDelivered    = ShopOrdersMain::where('ord_status', 'delivered')->count();     //for counting 
		
		return view('ShopPaypalSimple_AdminPanel.orders')
		    ->with(compact('shop_orders_main', 'countOrders', 'countProceeded', 'countNotProceeded', 'countDelivered')); 
	}
	
	
	
	
   /**
    * For ajax counting
    * @return int $count
    * 
    */
	public function countOrders(){
		$count = ShopOrdersMain::where('ord_status', 'not-proceeded')->count();
        if(!$count){
			$count = 0;
		}	
        	
		return $count;
	}
	
	
	
	
	/**
     * method to update Order Status in AdminPanel, from {public function orders()}. (E.g  change from 'proceeded' to 'not-proceeded')
     * @param  \Illuminate\Http\OrderStatusChangeRequest  $request
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function updateStatusField(OrderStatusChangeRequest $request)
	{
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('u_orderID')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
	    $orderID = $request->input('u_orderID');
		$orderStatus = $request->input('u_status');
		
		//check if user/admin wants to change the current Order status to the same value, e.g the status is 'proceeded' and admin wants change to the same 'proceeded;
	    $OneOrder = ShopOrdersMain::where('order_id', $request->input('u_orderID'))->first(); 
		
		if($OneOrder->ord_status == $request->input('u_status')){
		    return redirect()->back()->withInput()->with('flashMessageFailX', 'You tried to update Order <b>' . $orderID . ' </b> Status with the same value <b>' . ucfirst($orderStatus) . '</b>. It is unacceptable!!!' );

		} else { //it is OK to update
			ShopOrdersMain::where('order_id', $request->input('u_orderID'))->update([  'ord_status' => $orderStatus ]);
			return redirect()->back()->withInput()->with('flashMessageX', 'You successfully update Order <b>' . $orderID . ' </b> with new Status <b> ' . ucfirst($orderStatus) . '</b>' );
		}
	}
	
    //================================== End Orders view section =================================================

	
	
	
	
	
	
	
    //================================== Products view section =================================================
	
	
    /**
     * Display admin panel view with all shop products and option to edit, add new
     * @param  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function products()
    {
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		$allProducts   = ShopSimple::paginate(7); //all shop products with pagination
		$allCategories = ShopCategories::all();  //for <select> dropdown
		$allProductsSearchBar = ShopSimple::all();  // for Search Bar
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.shop-products-list')->with(compact('allProducts', 'allCategories', 'allProductsSearchBar'));  
	}
	
	
	
	
	/**
     * Displays admin page with a form to add a new product
     * @param  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function addProduct()
    {
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		$allCategories = ShopCategories::all();  //for <select> dropdown in form
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.add-product')->with(compact('allCategories'));  
	}
	
	
	
	
	/**
     * Saves new product to DB. Gets $_POST[''] sent by form in {public function addProduct()}
	 * Validation via Request Class
     * @param SaveNewProductRequest $request
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function storeProduct(SaveNewProductRequest $request)
    {
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('product-desr')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
		//getting Image info for Flash Message
		$imageName      = time(). '_' . $request->image->getClientOriginalName();
		$sizeInByte     = $request->image->getSize() . ' byte';
		$sizeInKiloByte = round( ($request->image->getSize() / 1024), 2 ). ' kilobyte'; //round 10.55364364 to 10.5
		$fileExtens     = $request->image->getClientOriginalExtension();
		//getting Image info for Flash Message
		
		//Move uploaded image to the specified folder 
		request()->image->move(public_path('images/ShopSimple'), $imageName);
		
		//saving a new product data to table {shop_simple}
		$data = $request->all(); 
		$shop = new ShopSimple();
		
		$shop->shop_title      = $data['product-name'];
		$shop->shop_descr      = $data['product-desr'];
		$shop->shop_price      = $data['product-price'];
		$shop->shop_image      = $imageName; //$request->image->getClientOriginalName();
		$shop->shop_currency   = '$' ;
		$shop->shop_categ      = $data['product-category'];
		$shop->sh_device_type  = $data['product-type'];
		$shop->shop_created_at = date('Y-m-d H:i:s');
				
		if($shop->save() ){
			
		    //saving qunatity to table {shop_quantity}. Must be transaction with $shop->save()
		    $quant = new ShopQuantity();
		    $quant->product_id   = $shop->shop_id;
		    $quant->all_quantity = $data['product-quant'];
			$quant->left_quantity = $data['product-quant']; // it is new, so qunatity is the same not ++
		    $quant->all_updated   =  date('Y-m-d H:i:s');
		    $quant->save();
		   
			if($quant->save()){
				
	            return redirect('/admin-products')/*->withInput()*/
		           ->with('flashMessageX', 'Validation was OK. Product<b> ' . $data['product-name'] .  ' </b> was saved to DB. Image was successfully uploaded. Image is <b> ' . $imageName  . '</b>. Size is ' . $sizeInByte . ' or ' . $sizeInKiloByte . '. Format is <b>' . $fileExtens . '</b>. Quantity ' . $data['product-quant'] . ' was loaded')
		           ->with('image',$imageName);
			}
			   
        } else {
	        return redirect('/admin-add-product')->withInput()->with('flashMessageFailX', 'Saving Failed');
		}
	}         
	
	
	
	
	
	
    /**
     * Display one product view by ID
     * @param  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function showOneProduct($id)
    {
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		//find the product by id
		$productOne = ShopSimple::where('shop_id', $id)->get();
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.adm-one-product')->with(compact('productOne'));  
	}
	
	
	
	
	
	/**
     * Display form to edit an existing product
     * @param  
     * @return \Illuminate\Http\Response
     */
	 
    public function editProduct($id)
    {
	    if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		 //additional check in case user directly intentionally navigates to this URL with not-existing ID
	    if (!ShopSimple::where('shop_id', $id)->exists()) { 
	        throw new \App\Exceptions\myException('Product ' . $id . ' does not exist');
	    }
		
		//find the product by id
		$productOne = ShopSimple::where('shop_id', $id)->get();
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.edit-product')->with(compact('productOne'));  
	}
	
	
	
	
	
	/**
     * Delete a one product. Gets $_POST sent by form in function products()
     * @param  
     * @return \Illuminate\Http\Response
     */
	 
    public function deleteProduct(Request $request)
    {
		//Rbac works on Delete request
		if(!Auth::user()->hasRole('admin')){ //arg $admin_role does not work
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
		
		//gets the id to delete
		$deleteID = $request->input('prod_id');
		
		
		//found image in 
		$product = ShopSimple::where('shop_id', $deleteID )->first();
		
		//geting quantity ID qunatity from table {shop_quantity} to delete later
		$quantID = $product->shop_id;
		
		//delete an actual image from folder '/images/ShopSimple/'
		if(file_exists(public_path('images/ShopSimple/' . $product->shop_image))){
		    \Illuminate\Support\Facades\File::delete('images/ShopSimple/' . $product->shop_image);
			$s = ' Image ' .  $product->shop_image . ' was removed from Folder /images/ShopSimple/.';
		} else {
			$s = ' Image removing crashed.';
		}
		
		if(ShopSimple::where('shop_id', $deleteID )->delete()){   //Delete){
			
			//geting quantity  from table {shop_quantity} by ID
			ShopQuantity::where('product_id', $quantID )->delete();
			
		    return redirect('/admin-products')/*->withInput()*/
		       ->with('flashMessageX', 'Deleted item <b> ' . $deleteID .  ' </b> successfully. ' . $s . '. Quantity removed from shop_quantity');
			   
        } else {
	        return redirect('/admin-products')->withInput()->with('flashMessageFailX', 'Deleting Failed');
		}
		
	}
	
	
	
	
	/**
     * Add++ quantity to table {shop_quantity}. Gets <form> data from page {'/admin-edit-product/{id}'}) (function editProduct())
     * @param  
     * @return \Illuminate\Http\Response
     */
	 
    public function addStockQuantity(Request $request)
    {
		
		//if $_POST['product-quant'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('product-quant')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
		
		$rules = [
			'product-quant' => ['required', 'integer',  ] , 
			//'productID'      => [ 'required', 'integer' ] , 
			
		];
		
	    //creating custom error messages. Should pass it as 3rd param in Validator::make()
	    $mess = [ 'product-quant.required' => 'We need quantity',
		          'product-quant.integer'  => 'We need quantity to be integer',
		        ];
		
	    $validator = Validator::make($request->all(),$rules, $mess);
	    if ($validator->fails()) {
			return redirect()->back()->withInput()->with('flashMessageFailX', 'Validation Failed' )->withErrors($validator);
	    }
		
		//check if ID exists
		if (!ShopQuantity::where('product_id', $request->input('prod-id'))->exists()) { 
	      throw new \App\Exceptions\myException('Product ' . $request->input('prod-id') . ' does not exist');
	    }
		
		//gets the Row
		$q = ShopQuantity::where('product_id', $request->input('prod-id'))->get();
		$allNewQuantity  = $q[0]->all_quantity  + $request->input('product-quant');
		$leftNewQuantity = $q[0]->left_quantity + $request->input('product-quant');
		
		if (ShopQuantity::where('product_id', $request->input('prod-id'))->update(['all_quantity' => $allNewQuantity, 'left_quantity' => $leftNewQuantity])) {
		    return redirect()->back()->withInput()->with('flashMessageX', 'Quantity added ' . $request->input('product-quant') . ' to ' . $q[0]->all_quantity . ' id ' .  $q[0]->product_id );

		} else {
			return redirect()->back()->with('flashMessageFailX', 'Sorry, Adding crashed');

		}
		

	}
	//================================== END Products view section =================================================
	
	

	
	
	
	
	
	
	
	
}
