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
//use Carbon\Carbon;

class ShopPayPalSimple_AdminPanel extends Controller
{
	public function __construct(){
		$this->middleware('auth');
        $this->middleware('\App\Http\Middleware\RbacMiddle::class'); //RBAC control => check if(!Auth::user()->hasRole('admin')
	}
	
	
   /**
    * Display Admin Panel start page
    * @return \Illuminate\Http\Response
    *
    * 
    */
    public function index()
    {   
		//RBAC middleware RbacMiddle goes here (via constructor)
        
        //delete unPaid orders from table {shop_orders_main} which are older than 24 hours
        $model = new ShopOrdersMain();
        $delete = $model->deleteOldOrders();
        
        //delete delete relevant rows in table {order_item} when u delete something in table {shop_orders_main}
        $model2 = new ShopOrdersItems();
        $message = $model2->deleteRelevantItemsFrom_Order_item($delete);
        
		return view('ShopPaypalSimple_AdminPanel.adminPanelMain')
            ->with(compact('message'));        
	}
	


    //==================================  Orders view section =================================================	
    
    /**
     * Display Admin Panel Orders page
     * @return \Illuminate\Http\Response
     *
     * 
     */
    public function orders()
    {
		//RBAC middleware RbacMiddle goes here (via constructor)
        
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
		    ->with(compact('shop_orders_main', 
                           'countOrders', 
                           'countProceeded', 
                           'countNotProceeded', 
                           'countDelivered')); 
	}
	
	
	
	
   /**
    * For ajax counting (used in public function index())
    * @return int $count
    * 
    */
	public function countOrders()
    { 
		//RBAC middleware RbacMiddle goes here (via constructor)
        
		$count = ShopOrdersMain::where('ord_status', 'not-proceeded')->count();
        if(!$count){
			$count = 0;
		}	
        	
		return $count;
	}
	
	
	
	
	/**
     * Method to update Order Status in AdminPanel, gets $_POST from {public function orders()}. (E.g  change from 'proceeded' to 'not-proceeded'). Validation class
     * @param  \Illuminate\Http\OrderStatusChangeRequest  $request
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function updateStatusField(OrderStatusChangeRequest $request)
	{
		//RBAC middleware RbacMiddle goes here (via constructor)
        
		//if $_POST['productID'] is not passed. In case the user navigates to this page by enetering URL directly, without submitting from with $_POST
		if(!$request->input('u_orderID')){
			throw new \App\Exceptions\myException('Bad request, You are not expected to enter this page.');
		}
		
	    $orderID     = $request->input('u_orderID');
		$orderStatus = $request->input('u_status');
		
		//check if user/admin wants to change the current Order status to the same value, e.g the status is 'proceeded' and admin wants change to the same 'proceeded;
	    $OneOrder = ShopOrdersMain::where('order_id', $request->input('u_orderID'))->first(); 
		
		if($OneOrder->ord_status == $request->input('u_status')){
		    return redirect()
                   ->back()
                   ->withInput()
                   ->with('flashMessageFailX', 'You tried to update Order <b>' . $orderID . ' </b> Status with the same value <b>' . ucfirst($orderStatus) . '</b>. It is unacceptable!!!' );

		} else { //it is OK to update
			ShopOrdersMain::where('order_id', $request->input('u_orderID'))->update([  'ord_status' => $orderStatus ]);
			return redirect()
                    ->back()
                    ->withInput()
                    ->with('flashMessageX', 'You successfully update Order <b>' . $orderID . ' </b> with new Status <b> ' . ucfirst($orderStatus) . '</b>' );
		}
	}
	
    //================================== End Orders view section =================================================

	
	
	
	
	
	
	
    //================================== Products view section =================================================
	
	
    /**
     * Display admin panel view with all shop products and option to edit, add new
     * 
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function products()
    {
		//RBAC middleware RbacMiddle goes here (via constructor)
		
		$allProducts   = ShopSimple::paginate(7); //all shop products with pagination
		$allCategories = ShopCategories::all();  //for <select> dropdown
		$allProductsSearchBar = ShopSimple::all();  // for Search Bar
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.shop-products-list')
            ->with(compact('allProducts', 'allCategories', 'allProductsSearchBar'));  
	}
	
	
	
	
	/**
     * Displays admin page with a form to add a new product
     * @param  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function addProduct()
    {
		//RBAC middleware RbacMiddle goes here (via constructor)
		
		$allCategories = ShopCategories::all();  //for <select> dropdown in form
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.add-product')
            ->with(compact('allCategories'));  
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
		//RBAC middleware RbacMiddle goes here (via constructor)
		
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
		$model = new ShopSimple();
        $save = $model->saveNewProduct($data, $imageName); 
        
		if($save['status'] == true){
			$lastID = $save['savedID'];
            
            //saving qunatity to table {shop_quantity}. Must be transaction with $shop->save()
            $modelQuant = new ShopQuantity();
			if($modelQuant->saveNewQuantity($data, $lastID)){
				
	            return redirect('/admin-products')/*->withInput()*/
		           ->with('flashMessageX', 'Validation was OK. Product<b> ' . $data['product-name'] .  ' </b> was saved to DB. Image was successfully uploaded. Image is <b> ' . $imageName  . '</b>. Size is ' . $sizeInByte . ' or ' . $sizeInKiloByte . '. Format is <b>' . $fileExtens . '</b>. Quantity ' . $data['product-quant'] . ' was loaded')
		           ->with('image',$imageName);
			} else {
                return redirect('/admin-add-product')
                   ->withInput()
                   ->with('flashMessageFailX', 'Saving of Quantity Failed');
            }
			   
        } else {
	        return redirect('/admin-add-product')
                ->withInput()
                ->with('flashMessageFailX', 'Saving Failed');
		}
	}         
	
	
		
	
    /**
     * Display one product view by ID
     * @param int $id  
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function showOneProduct($id)
    {
	    //RBAC middleware RbacMiddle goes here (via constructor)
		
		//find the product by id
		$productOne = ShopSimple::where('shop_id', $id)->get();
		
		return view('ShopPaypalSimple_AdminPanel.shop-products.adm-one-product')->with(compact('productOne'));  
	}
	
	
	
	
	
	/**
     * Display form to edit an existing product
     * @param int $id  
     * @return \Illuminate\Http\Response
     */
	 
    public function editProduct($id)
    {
	    //RBAC middleware RbacMiddle goes here (via constructor)
		
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
     * @param Request $request 
     * @return \Illuminate\Http\Response
     */
	 
    public function deleteProduct(Request $request)
    {
	    //RBAC middleware RbacMiddle goes here (via constructor)

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
	        return redirect('/admin-products')
                ->withInput()->with('flashMessageFailX', 'Deleting Failed');
		}
		
	}
	
	
	
	/**
     * Add++ quantity to table {shop_quantity}.Handles $_POST request. Gets <form> data from page {'/admin-edit-product/{id}'}) (function editProduct())
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
	 
    public function addStockQuantity(Request $request)
    {
		//RBAC middleware RbacMiddle goes here (via constructor)

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
