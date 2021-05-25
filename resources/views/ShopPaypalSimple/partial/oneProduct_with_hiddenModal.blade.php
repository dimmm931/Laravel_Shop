<?php
//Is used both in /views/ShopPaypalSimple/shopIndex.blade.php and /views/ShopPaypalSimple/showOneProduct.blade.php
//Show One product with hidden modal
//<!-- Show One product with hidden modal. Used to render partial in loop(shopIndex.blade.php) or separately (when show 1 product from SearchBar) (showOneProduct.blade.php) -->
//accepts 2 arg: arg[0] - is an iterator (to use in loop or for single record, arg[1] - is an object with data )
//to work 100% must include js :
   // <script src="{{ asset('js/ShopPaypalSimple/LazyLoad/jquery.lazyload.js')}}"></script> <!--Lazy Load lib JS-->
   //<!-- Sweet Alerts -->
   //<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
   //<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->
   //<script src="{{ asset('js/ShopPaypalSimple/shopSimple.js')}}"></script>

				
//check if product already in cart, if Yes-> get its quantity, if no-. sets to 1
if (isset($_SESSION['cart_dimmm931_1604938863']) && isset($_SESSION['cart_dimmm931_1604938863'][$allDBProducts[$i]['shop_id']])){
    $quantityX = $_SESSION['cart_dimmm931_1604938863'][$allDBProducts[$i]['shop_id']]; //gets the quantity from cart
} else {
	$quantityX = 1;
}
?>		

<!-- Visible by default -->		   	
<div id="{{$allDBProducts[$i]['id']}}" class="col-sm-5 col-xs-12  list-group-item bg-success cursorX shadowX modal-trigger" data-toggle="modal" data-target="#myModal{{$i}}" data-quantityZ="{{$quantityX}}"> <!--data-toggle="modal" data-target="#myModal' . $i .   for modal -->
    <div class="col-sm-4 col-xs-4">             {{$allDBProducts[$i]['shop_title']}} </div>
	<div class="col-sm-3 col-xs-4 word-breakX"> {{$allDBProducts[$i]['shop_price']}}   {{$allDBProducts[$i]['shop_currency']}}</div>
	<div class="col-sm-2 hidden-xs"> <!-- hidden in mobile --> {{$model->truncateTextProcessor($allDBProducts[$i]['shop_descr'], 8) }}  </div>  <!-- hidden in mobile --> 	
	<div class="col-sm-3 col-xs-4">				
	    <!--Image with lazyLoad-->
	    <!--<img class="lazy my-one" src="{{URL::to("/")}}/images/ShopSimple/{{$allDBProducts[$i]['shop_image'] }}"  alt="a" />-->
		<img class="lazy my-one" data-original="{{URL::to("/")}}/images/ShopSimple/{{$allDBProducts[$i]['shop_image'] }}"  alt="a" />
	</div>   
</div>
				 
<!--adds vertical space after 2 divs with goods-->
@if($i%2 != 0 )
	<div class="col-sm-12 col-xs-12"><!-- even -->-</div>
@else 
    <!--add horizontal space between 2 goods-->
	<div class="col-sm-1 col-xs-1"><!-- s --></div>
@endif
						
		                	
<!--------- Hidden Modal Window with one clicked item (category, description, price and other additional info)---------->
<div class="modal fade" id="myModal{{$i}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
				    <i class="fa fa-delicious" style="font-size:3em; color: navy;"></i> 
					<b> Product</b> <span class="small-x"> 
					
					<!-- show "direct URL" link only if current url contains route "/shopSimple", don't show if "/showOneProduct/{id}" -->
					@if (in_array(Route::getFacadeRoot()->current()->uri(), ['shopSimple']))
					    <a href="{{ route('showOneProduct', ['id'=> $allDBProducts[$i]['shop_id'] ]) }}"> open direct URL</a></span>
				    @endif
					
				</h4> 
		        <?php
				//checks if this product already in the cart
				if (isset($_SESSION['cart_dimmm931_1604938863']) && isset($_SESSION['cart_dimmm931_1604938863'][$allDBProducts[$i]['shop_id']])){
				    echo "<p class='text-danger'>Already " . $_SESSION['cart_dimmm931_1604938863'][$allDBProducts[$i]['shop_id']] . " items was added to the cart.</p>";
				} else {
					echo "not in cart";
				}
				?>
            </div>
					   
            <div class="modal-body">
                <p><b> {{$allDBProducts[$i]['shop_title']}} </b></p>
						  
				<div class="row list-group-item">
				    <div class="col-sm-1 col-xs-3">Price</div>
					<div class="col-sm-4 col-xs-9"><span class="price-x"> {{$allDBProducts[$i]['shop_price']}} </span> {{$allDBProducts[$i]['shop_currency']}}  </div> 
				</div>
				
                <!-- Description -->				
				<div class="row list-group-item">  
					<div class="col-sm-1 col-xs-3">Info</div>
				    <div class="col-sm-11 col-xs-9"> {{$allDBProducts[$i]['shop_descr']}} </div> 
				</div>
						  
				<div class="row list-group-item">
				    <div class="col-sm-1 col-xs-3">Category</div>
				    <div class="col-sm-4 col-xs-9"><i> {{$allDBProducts[$i]->categoryName->categ_name}} </i></div> <!--hasOne(NOT hasMany) relation in '/model/ShopSimple' on table {shop_categories} -->
				</div>
				

                <!--- Quantity of product left in stock, from table {shop_quantity} hasOne) -->
				<div class="row list-group-item">
				    <div class="col-sm-1 col-xs-3">Left</div>
					<div class="col-sm-9 col-xs-9"> {{$allDBProducts[$i]->quantityGet->left_quantity }} items</div> <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
				</div>
				
			    <!--- Total product sum calculation (2x16.64=N) -->
				<div class="row list-group-item">
				    <div class="col-sm-1 col-xs-3">Total</div>
					<div class="col-sm-9 col-xs-9 shadowX"><span class="sum"></span></div> 
				</div>
						  
						  
				<div class="row list-group-item">
					<div class="col-sm-1 col-xs-3">Image</div>
				    <div class="col-sm-8 col-xs-9"><img class="my-one-modal" src="{{URL::to("/")}}/images/ShopSimple/{{$allDBProducts[$i]['shop_image']}}"  alt="a"/></div>
				</div>  
					 
            </div>
					 
 
			<!---------- Section ++button /form input/--button ------->
			<div class="row">
					 
			    <!--- Empty div to keep distance -->
			    <div class="col-sm-4 col-xs-2"> 
			    </div>
					    
						
			    <!--- Plus button, contains additional data: price, currency, quantity left -->
			    <div class="col-sm-1 col-xs-2"> 
				    <button type="button" class="btn btn-primary button-plus" 
				        data-currX="{{$allDBProducts[$i]['shop_currency']}}" 
						data-priceX="{{$allDBProducts[$i]['shop_price']}}" 
						data-quantLeft="{{$allDBProducts[$i]->quantityGet->left_quantity }}"><!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
				         +
				    </button> 

			    </div>
						 
						 
						
			    <!-- form with input -->
			    <div class="col-sm-2 col-xs-3">
							 		 
			        <!------- New form (with button "add to cart") -------->
				    <form method="post" class="form-assign" action="{{url('/addToCart')}}">
					    <input type="hidden" value="{{csrf_token()}}" name="_token"/>
					    <input type="number" value="{{$quantityX}}" name="yourInputValue" class="item-quantity form-control" />
					    <input type="hidden" value="{{$allDBProducts[$i]['shop_id']}}" name="productID" />
					    </br><input type="submit" class="btn btn-primary shadowX submitX rounded" value="Add to cart"/>
				    </form>
				    <!-- end New form (with button "add to cart") -------->
				
				
				    <!------- New form (with button "Buy in one click") -------->
				    <form method="post" class="form-assign" action="{{url('/addToCart')}}">
					    <input type="hidden" value="{{csrf_token()}}" name="_token"/>
					    <input type="hidden" value="{{$allDBProducts[$i]['shop_id']}}" name="productID" />
					    </br><input onclick="alert('Not implemented yet. Add to cart');return false;" type="submit" class="btn btn-primary shadowX rounded" value="Buy in one click"/>
				    </form>
				    <!-- end New form (with button "Buy in one click")-->
			    </div>		  
			    <!-- End form with input -->
						  
						  
			    <!--- Minus button -->
			    <?php
			    //getting flag, used to detect if product is already in cart
			    if (isset($_SESSION['cart_dimmm931_1604938863']) && isset($_SESSION['cart_dimmm931_1604938863'][$allDBProducts[$i]['shop_id']])){
				    $ifInCartFlag = " data-cartFlag ='true'";
			    } else {
				    $ifInCartFlag = " data-cartFlag ='false' ";
			    }
			    ?>
			    <div class="col-sm-1 col-xs-2"> 
			        <button type="button" class="btn btn-danger button-minus" data-currX="{{$allDBProducts[$i]['shop_currency']}}"  data-priceX="<?php echo $allDBProducts[$i]['shop_price'].'"'; echo $ifInCartFlag; ?>>-</button>
			    </div>
						 
                <!--- Empty div to keep distance -->						 
			    <div class="col-sm-3 col-xs-3">
			    </div>
						  
		    </div>
			<!---------- END Section ++button /form input/--button ------->
			  
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!------------ End Hidden Modal Window with one clicked item ---------------> 
		