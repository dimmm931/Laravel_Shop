<?php
//Show payment page. Show submitted on prev page Shipping form (address, phone etc ) & buttons to Pay.
//THIS PAGE NO LONGER USES $_SESSION['cart_dimmm931_1604938863'] to store and retrieve user's cart; Format is { [8]=> int(3) [1]=> int(2) [4]=> int(1) }
//THIS PAGE NO LONGER USES $_SESSION['cart_dimmm931_1604938863'] or $input => Values are now derived from DB by passed Order ID
?>

@extends('layouts.app')

@section('content')

<!-- Include js/css file for this view only -->
<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet"> 

<!-- Sweet Alerts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->
<!-- Include js file for thisview only -->


<div id="allPrev" class="container animate-bottomPrev">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="panel panel-default xo">
				
			    <!-- Flash message if Success -->
				@if(session()->has('flashMessageX'))
                    <div class="alert alert-success">
                        {!! session()->get('flashMessageX') !!} <!--Displays content without html escaping -->
                    </div>
                @endif
				<!-- Flash message -->
				

                <!-- Flash message if Failed -->
				@if(session()->has('flashMessageFailX'))
                    <div class="alert alert-danger">
                        {!! session()->get('flashMessageFailX') !!} <!--Displays content without html escaping -->
                    </div>
                @endif
				<!-- Flash message if Failed -->				
				

                <!-- Display form validation errors var 2 -->
				@if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                <!-- End Display form validation errors var 2 -->				
					
					
                <div class="panel-heading text-warning">
				    Pay your order <span class="small text-danger">*</span> 
					<!-- Link to go back -->
				    <div>
				        &nbsp;<i class="fa fa-hand-o-left" style="font-size:24px"></i>
				        <a href="{{ url('/shopSimple') }}"> back to shop </a>
                    </div>
				</div>

                <div class="panel-body shop">
				
				    <div class="col-sm-10 col-xs-10">
                        <h1>Pay your order</h1>
		            </div>	
				
				    <!-------- Cart icon with badge ----------->
		            <?php 
		            //get the car quantity
		            if (isset($_SESSION['cart_dimmm931_1604938863'])) { 
		                $c = count($_SESSION['cart_dimmm931_1604938863']); 
		            } else { 
			           $c = 0; 
		            } ?>
		
		            <div class="col-sm-2 col-xs-2 badge1 bb" data-badge="<?php echo $c; ?> ">
					    <a href="{{ route('cart') }}"> <i class="fa fa-cart-plus fa-4x" aria-hidden="true"></i> </a>
                    </div>
                    <!-------- Cart icon with badge ----------->
		
		
		
		
				    <!-------------------- Progress Status Icons by component ----------------->
	                <!--display shop timeline progress via Helper => Progress Status Icons-->
					<div class="col-sm-12 col-xs-12">
	                    {!! \App\Http\MyHelpers\ShopSimple\ShopTimelineProgress2::showProgress2("Payment") !!}
					</div>
	                <!--------------  END  Progress Status Icons by component ----------------->
					  
					
					
					
					@if (!isset($_SESSION['cart_dimmm931_1604938863']) || (count($_SESSION['cart_dimmm931_1604938863']) == 0) )
		               <div class="col-sm-12 col-xs-12"> 
                           <br><br><br>
                           <center>
				               <h2> So far there is nothing to pay for  </h2>
		                       <i class='fa fa-question-circle-o' style='font-size:58px;color:red'></i></center>
					    </div>
	                @else 
						
                        
                        
					    <!------------  CART Products List ------------->
                        <div class="row shop-items"><hr>
	                        <div class="col-sm-12 col-xs-12 shadowX"><h3>You have <?=count($_SESSION['cart_dimmm931_1604938863']);?> items to Check-out</h3></div>
		 
		                        <!-- THEAD -->
		                        <div class="col-sm-12 col-xs-12  list-group-item shadowX">
		                            <div class="col-sm-4 col-xs-3">Name</div>
			                        <div class="col-sm-2 hidden-xs">Image</div> <!-- hidden in mobile -->
			                        <div class="col-sm-2 col-xs-4">Price</div>
			                        <div class="col-sm-2 col-xs-2">Quant</div>
			                        <div class="col-sm-2 col-xs-3">Sum</div>
		                        </div>
		                        <!-- End THEAD -->
	      
		                        <!-------------------------------------- Foreach $_SESSION['cart'] to dispaly all cart products --------------------------------------------->
		                        <?php
				                $startSec      = time(); //seconds 
				                $startMicroSec = microtime(true); //microseconds
		                        $i             = 0;	
                                $totalSum      = 0;

				                //var_dump($_SESSION['cart_dimmm931_1604938863']); 
				                //echo "</br>";
				                ?>
				 
		                        @foreach($_SESSION['cart_dimmm931_1604938863'] as $key => $value)
		                            <?php
				                    $i++;
			                        //echo "<p>key " . $key . "</p>";
				                    //FIX, should find by column iD 'shop_id'
				                    $keyN = array_search($key, array_column($inCartItems, 'shop_id')); //returns 3
				                    //echo "<p>found keyN " . $keyN . "</p>";
				                    ?>						    						

			                        <!-- Start Display product -->
		                            <div id="{{$inCartItems[$keyN]['shop_id'] }}" class="col-sm-12 col-xs-12  list-group-item bg-success cursorX" data-toggle="modal" data-target="#myModal{{$i}}"> <!--  //data-toggle="modal" data-target="#myModal' . $i .   for modal -->
			                  
							            <!-- Display product name ( + in mobile shows image) --> 
				                        <div class="col-sm-4 col-xs-3"> {{$inCartItems[$keyN]['shop_title'] }} 
				                            <!-- image visible for mobile only -->
				                            <div class="visible-xs"><img class="my-one" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" /></div>
				                        </div> <!--name-->
			     
				                        <!--Dispalay image--> <!-- hidden in mobile -->
				                        <div class="col-sm-2 hidden-xs"> <!-- hidden in mobile -->
				                            <img class="my-one" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" />
                                        </div>
				 
				                        <!-- Display Price -->
			                            <div class="col-sm-2 col-xs-4 word-breakX font-mobile"> 
                                            <span class="priceX">{{$inCartItems[$keyN]['shop_price']}} </span> {{$inCartItems[$keyN]['shop_currency']}} 
                                        </div>
				 
				                        <!--Display quantity -->
		                                <div class="col-sm-2 col-xs-2"> <!-- // . $_SESSION['cart_dimmm931_1604938863'][$keyN]; //quantity-->
				                            <?php
				                            $quantityX = $_SESSION['cart_dimmm931_1604938863'][$key]; //gets the quantity
				                            ?>
					                        {{$quantityX}} <!-- Quantity -->
				                        </div>   <!--quantity-->	
				                        {{--END quantity div => --}}
				
				
				                        {{--Sum for one product--}} {{--total sum for this product, price*quantity--}}
			                            <div class="col-sm-2 col-xs-3 one-pr-sum font-mobile">
                                            {{ ($_SESSION['cart_dimmm931_1604938863'][$key]*$inCartItems[$keyN]['shop_price']) }} {{$inCartItems[$keyN]['shop_currency']}}
                                        </div>   
				     
		                            </div>
				 
			                        <?php
			                        $totalSum+= $_SESSION['cart_dimmm931_1604938863'][$key]*$inCartItems[$keyN]['shop_price']; //Total sum for this one product (2x16.64=N)
		                            ?>
                                    
		                        @endforeach
		 
		                        <?php
		                        $endSec = time();
		                        $endtMicroSec = microtime(true);
		                        //echo "<p>BenchMark Session(Real Host)=> " . ($endSec - $startSec) . " sec vs " . ($endtMicroSec - $startMicroSec) . " microsec.</p>" ;
		                        ?>
	                        </div> <!-- row shop-items -->
	  
	  
	                        <!-- Total sum for all products -->
	                        <div class="col-sm-12 col-xs-12 shadowX">
	                            <h3>Total: </h3>
		                        <h2 id="finalSum"> {{ $totalSum }}  {{$inCartItems[$keyN]['shop_currency']}}</h2><hr> <!-- ₴ -->
	                        </div>
	 
	                     
	  
	                        <!--  getting Order detail from DB by ID {$savedID} passed from {function pay1} -->
	                        <div class="col-sm-12 col-xs-12 shadowX">
	                            <hr>
	                            <p> Order Id <i class="fa fa-check-square-o"></i>  {{ $thisOrder[0]->ord_uuid }}   </p>
	                            <h3> Shipping details </h3>
                                <p></p><hr>
                                <!--<p>Data retrieved from DB by passed from {function pay1}Order ID savedID</p>-->		  
		                        <p> <i class="fa fa-address-card-o"></i>  {{ $thisOrder[0]->ord_name   }}   </p>
		                        <p> <i class="fa fa-archive"></i>         {{ $thisOrder[0]->ord_email   }}   </p>
		                        <p> <i class="fa fa-arrows"></i>          {{ $thisOrder[0]->ord_address }}   </p>
		                        <p> <i class="fa fa-bell-o"></i>          {{ $thisOrder[0]->ord_phone }}   </p>
	                        </div>
	  
	                        <?php
	                        //description for LiqPay button/form. Products in order
	                        $descriptionX = "";
	                        ?>
	  
	  
	                        <!-- Display ordered Items from SQL DB -->
	                        <!-- Display Items of this Order by hasMany and hasOne. Order details comes from table {shop_order_item}-->
	                        <div class="col-sm-12 col-xs-12 shadowX ord-list">
	                            <hr>
	                            <!-- additionall check (in case u saved order to table {shop_orders_main} but saving to table {shop_order_item} failed and therefore table {shop_order_item} does not have related/corresponded column to  {shop_orders_main}) -->
	                            @if( $thisOrder[0]->orderDetail->isEmpty() ) <!-- hasMany relation, model {ShopOrdersMain} connects by ID to model {ShopOrdersItems} -->
		                            corrupted data
		                        @else
				
			  
		                            <!-- Shows related data from table {shop_order_item} via hasMany realtionShip, hasMany must be inside second foreach -->
		                            <!-- Shows order details, i.e  HP notebook 4530S, 2 pcs * 287.36 ₴ = 574.72 ₴; Canon EOS R, 2 pcs * 2354.16 ₴ = 4708.32 ₴ -->
			                        @foreach ($thisOrder[0]->orderDetail as $x) <!-- hasMany -->
			                            <?php
			                            $descriptionX.= $x->productName2->shop_title . " " .  $x->items_quantity . " items. "
			                            ?>
		                                <div class="border">
			  
			                                <!-- product title -->
				                            <div class="col-sm-12 col-xs-12">		
			                                    <p><b><i class="fa fa-paperclip"></i> {{$x->productName2->shop_title}} </b></p> <!-- hasOne --> <!--hasOne function {productName} from model {ShopOrdersItems} connects by id to model {ShopSimple} -->
			                                </div>
				  
			                                <!-- image -->
			                                <div class="col-sm-12 col-xs-12">
				                                &nbsp;&nbsp;<img class="my-one-2" src="{{URL::to("/")}}/images/ShopSimple/{{$x->productName2->shop_image }} "alt="a" /><!-- hasOne --> <!--hasOne function {productName} from model {ShopOrdersItems} connects by id to model {ShopSimple} -->
				                            </div>
				  
				                            <!-- quantity * price = sum -->
				                            <div class="col-sm-12 col-xs-12">
				                                <div class="col-sm-4 col-xs-3">  {{$x->items_quantity}} <span class="hidden-xs">pcs</span>  </div> <!-- .hidden-xs = visible in desktop only -->
					                            <div class="col-sm-4 col-xs-4">  {{$x->item_price}} ₴       </div> 
					                            <div class="col-sm-4 col-xs-5">  {{$x->items_quantity * $x->item_price }} ₴ </div> {{-- quantity * price = sum  --}} <!-- hasOne --> 
		                                        <hr>
				                            </div>
				  
			                            </div>		  
		                            @endforeach
		                            <!-- End hasMany realtionShip! -->
			                        <!-- END Display ordered Items from SQL DB -->
						   
		                        @endif	 				   
	                        </div>  <!-- end .ord-list --> <!-- hasMany. Order details from table {shop_order_item}-->


                            <!-------  REAL PAYPAL Payment Button ------>
	                        <?php
	                        $payNowButtonUrl = env('PAYPAL_PAYNOW_BUTTON_URL', 'screw'); //'https://www.sandbox.paypal.com/cgi-bin/websc';
	                        $receiverEmail   = env('PAYPAL_RECEIVER_EMAIL', 'screw');    //'sb-qwtmd3901800@personal.example.com'; //email получателя платежа(на него зарегестрирован paypal аккаунт) 

                            $productId = 1;
                            $itemName  = 'Complex Order';	// название продукта
                            $amount    = $totalSum; //'1.0'; // цена продукта(за 1 шт.)
                            $quantity  = 1;	// количество

                            $returnUrl  = env('PAYPAL_RETURN_URL', 'screw'); //'http://account93.zzz.com.ua/laravel_CPH/public/pay-or-fail?status=paymentSuccess';
                            $customData = ['user' => 'Dima', 'product_id' => $productId, 'myOrderID' => $thisOrderID ];
                            ?>

                            <form action="<?php echo $payNowButtonUrl; ?>" method="post">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="<?php echo $receiverEmail; ?>">
                                <input id="paypalItemName" type="hidden" name="item_name" value="<?php echo $itemName; ?>">
                                <input id="paypalQuantity" type="hidden" name="quantity" value="<?php echo $quantity; ?>">
                                <input id="paypalAmmount" type="hidden" name="amount" value="<?php echo $amount; ?>">
                                <input type="hidden" name="no_shipping" value="1">
                                <input type="hidden" name="return" value="<?php echo $returnUrl; ?>">
                                <input type="hidden" name="custom" value="<?php echo json_encode($customData);?>">
                                <input type="hidden" name="currency_code" value="USD">
                                <input type="hidden" name="lc" value="US">
                                <input type="hidden" name="bn" value="PP-BuyNowBF">
                                <button type="submit" class="btn btn-primary" style="font-size:18px">
                                     Pay with PayPal <?=$totalSum?> ₴ <i class="fa fa-cc-paypal"></i>   
                                </button>
                            </form><hr><hr>
	                        <!-------  END REAL PAYPAL ------>   
 
	

                            <!-------  Real LiqPay Button ------>  
                            <div class="col-sm-12 col-xs-12">
                                <?php
		                        $LiqPayButton = $liqpay->cnb_form(array(
                                    'action'         => 'pay',
                                    'amount'         => $totalSum, //'22',
                                    'currency'       => 'USD',
                                    'description'    => $descriptionX,
                                    'order_id'       => $thisOrder[0]->ord_uuid,
                                    'version'        => '3',
                                    'language'       => 'en',
				                    'result_url'     => env('LIQPAY_RETURN_URL', 'screw'), //'http://account93.zzz.com.ua/laravel_CPH/public/pay-or-fail?status=paymentSuccess', //URL в Вашем магазине на который покупатель будет переадресован после завершения покупки.
				                    'server_url'     => '' //URL API в Вашем магазине для уведомлений об изменении статуса платежа (сервер->серв
                                ));
		                        echo $LiqPayButton;
		                        ?>
                            </div>
                            <!-------  LiqPay Button ------>

		
                            <div class="col-sm-12 col-xs-12"> <!-- just Spacing -->
	                            <hr>
	                        </div>
	   
	    
	                        <div class="col-sm-12 col-xs-12">
	                            <p>Cards for testing LiqPay</p>
                                <p>4242424242424242	Successful payment</p>
                                <p>000000000003063	Successful payment with 3DS</p>
                                <p>4000000000003089	Successful payment with OTP</p>
                                <p>4000000000003055	Successful payment with CVV</p>
                                <p>4000000000000002	Failure payment. Error code - limit</p>
                                <p>4000000000009995	Failure payment. Error code - 9859 </p>
                                <p>sandbox_token	Successful payment with token      </p>
                            </div>
  
              
	                    @endif
                    					
				    </div> <!-- end .shop --> 
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->

<script>
</script>
@endsection