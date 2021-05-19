@extends('layouts.app')
<?php
//uses $_SESSION['cart_dimmm931_1604938863'] to store and retrieve user's cart; Format is { [8]=> int(3) [1]=> int(2) [4]=> int(1) }
?>

@section('content')
<!-- Include js/css file for this view only -->
<script src="{{ asset('js/ShopPaypalSimple/shopSimple_Loader.js')}}"></script> <!-- CSS Loader -->
<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet">
<link href="{{ asset('css/ShopPaypalSimple/shopSimple_Loader.css') }}" rel="stylesheet">

<!-- Sweet Alerts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->

<link href="{{ asset('css/ShopPaypalSimple/cart.css') }}" rel="stylesheet"> <!-- Cart CSS  -->
<script src="{{ asset('js/ShopPaypalSimple/cart.js')}}"></script>           <!-- Cart JS  -->
<!-- Include js file for this view only -->

<div id="all" class="container animate-bottom">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="panel panel-default xo">
		
			    <!-- Flash message if Success -->
				@if(session()->has('flashMessageX'))
                    <div class="alert alert-success">
                        {!! session()->get('flashMessageX') !!} <!--Displays content without html escaping -->
                    </div>
                @endif
				
                <!-- Flash message if Failed -->
				@if(session()->has('flashMessageFailX'))
                    <div class="alert alert-danger">
                        {!! session()->get('flashMessageFailX') !!} 
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
				    Shop Cart <span class="small text-danger">*</span> 
				</div>

                <div class="panel-body shop">
				    <div class="col-sm-10 col-xs-10">
                        <h1>Shop PayPal Cart</h1>
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
	                    {!! \App\Http\MyHelpers\ShopSimple\ShopTimelineProgress2::showProgress2("Cart") !!}
					</div>
	                <!--------------  END  Progress Status Icons by component ----------------->
					
					@if (!isset($_SESSION['cart_dimmm931_1604938863']) || (count($_SESSION['cart_dimmm931_1604938863']) == 0) )
		                <div class="col-sm-12 col-xs-12"> 
                            <br><br><br>
                            <center>
				                <h2> So far the cart is empty  <i class='fa fa-cart-arrow-down' aria-hidden='true'></i></h2>
		                        <i class='fa fa-question-circle-o' style='font-size:58px;color:red'></i>
                            </center>
					   </div>
	                @else 
                    
  
                    <!------------  CART Products List ------------->
                    <div class="row shop-items">
	                    <div class="col-sm-12 col-xs-12 shadowX">
                            <h3>You have <?=count($_SESSION['cart_dimmm931_1604938863']);?> items in your cart </h3>
                        </div>
		 
		                <!-- THEAD -->
		                <div class="col-sm-12 col-xs-12  list-group-item shadowX">
		                    <div class="col-sm-4 col-xs-3">Name</div>  
			                <div class="col-sm-2 hidden-xs">Image</div> <!-- hidden in mobile -->
			                <div class="col-sm-2 col-xs-3">Price</div>
			                <div class="col-sm-2 col-xs-3">Quant</div>
			                <div class="col-sm-2 col-xs-3">Sum</div>
		                </div>
		                <!-- End THEAD -->
	      
		                <!-------------------------------------- Foreach $_SESSION['cart'] to dispaly all cart products --------------------------------------------->
		                <?php
				        $startSec      = time(); //seconds 
				        $startMicroSec = microtime(true); //microseconds
		                $i             = 0;	
                        $totalSum      = 0;
		                ?>
		  
		                <form method="post" class="form-assign" action="{{url('/checkOut')}}">
				            <input type="hidden" value="{{csrf_token()}}" name="_token"/>
				 
		                    @foreach($_SESSION['cart_dimmm931_1604938863'] as $key => $value)
		                        <?php
				                $i++;
			                    //find in $inCartItems index the product by id. MEGA FIX => use array_search(($key-1),... instead of array_search($key-1, ...
				                //FIX, should find by column iD 'shop_id'
				                $keyN = array_search($key, array_column($inCartItems, 'shop_id')); //returns 3
				                ?>						    						

			                    <!-- Dispalay products -->
		                        <div id="{{$inCartItems[$keyN]['shop_id'] }}" class="col-sm-12 col-xs-12  list-group-item bg-success cursorX" data-toggle="modal" data-target="#myModal{{$i}}"> <!--  //data-toggle="modal" data-target="#myModal' . $i .   for modal -->
			     
				                    <!-- Display product name ( + in mobile shows image) -->
				                    <div class="col-sm-4 col-xs-3"> 
				                        {{$inCartItems[$keyN]['shop_title'] }} 
				                        <!-- image visible for mobile only -->
				                        <div class="lazy my-one visible-xs"><img class="img-mobile" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" /></div>
				                    </div> <!--name-->
				 
				                    <!-- Image --> <!-- hidden in mobile -->
			                        <div class="col-sm-2 hidden-xs"> 
				                        <img class="lazy my-one" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" />
                                    </div>
				 
				                    <!-- Display Price -->
			                        <div class="col-sm-2 col-xs-3 word-breakX font-mobile"> 
                                        <span class="priceX">{{$inCartItems[$keyN]['shop_price']}} </span>  {{$inCartItems[$keyN]['shop_currency']}} 
                                    </div>
				 
				                    <!--Display quantity & ++/-- buttons (div with form inputs)-->
		                            <div class="col-sm-2 col-xs-3"> <!-- // . $_SESSION['cart_dimmm931_1604938863'][$keyN]; //quantity-->
				                        <?php
				                        $quantityX = $_SESSION['cart_dimmm931_1604938863'][$key]; //gets the quantity
					                    ?>
                                        <input type="hidden" value="{{$inCartItems[$keyN]['shop_id']}}" name="productID[]" />
					                    <input type="number" value="{{$quantityX}}" name="yourInputValueX[]" class="form-control item-quantity font-mobile" /> <!-- Quantity -->
					
					                    <!--- Plus/Minus buttons -->
                                        <button type="button" class="btn btn-danger   inline-btn btnCart-minus" data-currX="{{$inCartItems[$keyN]['shop_currency']}}"  data-priceX="{{$inCartItems[$keyN]['shop_price']}}"> - </button>					
				                        <button type="button" class="btn btn-primary  inline-btn btnCart-plus"  data-currX="{{$inCartItems[$keyN]['shop_currency']}}"  data-priceX="{{$inCartItems[$keyN]['shop_price']}}">+</button>
				                    </div>   <!--quantity-->	
				                    {{--END quantity div => --}}
				
				
				                    {{--Sum for one product--}}
			                        <div class="col-sm-2 col-xs-3 one-pr-sum font-mobile">
                                        {{--total sum for this product, price*quantity--}}
                                        {{ ($_SESSION['cart_dimmm931_1604938863'][$key]*$inCartItems[$keyN]['shop_price']) }} {{$inCartItems[$keyN]['shop_currency']}}
                                    </div>  
				     
		                        </div>
				 
			                    <?php
			                    $totalSum+= $_SESSION['cart_dimmm931_1604938863'][$key]*$inCartItems[$keyN]['shop_price']; //Total sum for this one product (2x16.64=N)
		                        ?>
		                    @endforeach
		 
		                    <input type="submit" class="btn btn-info btn-lg shadowX" value="Check-out">
		                </form>
		 
		 
		                <?php
		                $endSec = time();
		                $endtMicroSec = microtime(true);
		                //echo "<p>BenchMark Session(Real Host)=> " . ($endSec - $startSec) . " sec vs " . ($endtMicroSec - $startMicroSec) . " microsec.</p>" ;
		                ?>
	                </div> <!-- row shop-items -->
	  
	  
	                <!-- Total sum for all products -->
	                <div class="col-sm-12 col-xs-12 shadowX">
	                    <h3>Total: </h3>
		                <h2 id="finalSum"> {{ $totalSum }}  {{$inCartItems[$keyN]['shop_currency']}}</h2> <!-- ₴ -->
	                </div>
  
                    <div class="col-sm-12 col-xs-12">
	                    <hr>
	                </div>
  
                @endif
  
			    </div> <!-- end .shop -->
				      
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->

@endsection