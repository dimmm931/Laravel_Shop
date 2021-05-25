<?php
//show checkout page. CheckOut == Order. Shows selected products + Shipping form (address, phone etc). User has an option to login/register or proceed unlogged
//uses $_SESSION['cart_dimmm931_1604938863'] to store and retrieve user's cart; Format is { [8]=> int(3) [1]=> int(2) [4]=> int(1) }//uses $_SESSION['orderID_1604938863'] to store user's Order ID (stores table 'shop_orders_main' increment ID, not UUID)

?>

@extends('layouts.app')
@section('content')

<!-- Include js/css file for this view only -->
<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/ShopPaypalSimple/check-out.css') }}" rel="stylesheet">

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
				    CheckOut <span class="small text-danger">*</span> 
					<!-- Link to go back -->
				    <div>
				        &nbsp;<i class="fa fa-hand-o-left" style="font-size:24px"></i>
				        <a href="{{ url('/shopSimple') }}">back to shop </a>
                    </div>
				</div>

                <div class="panel-body shop">
				    <div class="col-sm-10 col-xs-10">
                        <h1> CheckOut </h1>
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
	                    {!! \App\Http\MyHelpers\ShopSimple\ShopTimelineProgress2::showProgress2("Order") !!}
					</div>
	                <!--------------  END  Progress Status Icons by component ----------------->
					
                    
                    <!-- If order has been planted already and user comes back to this page -->
                    @if (isset($_SESSION['cart_dimmm931_1604938863']))
                        <div class="col-sm-12 col-xs-12 alert alert-danger"> <br><br>
                            <center>
				                <h3> Your order already exist. Want to override? If not, proceed to 
                                    <p><hr><a href="<?=route('/payPage2')?>"> Payment <i class="fa fa-cc-mastercard" style="font-size:24px;"></i></a></p>
		                        </h3>
                            </center>
					    </div>    
					@endif
                    
                    <!-- If cart is empty -->
					@if (!isset($_SESSION['cart_dimmm931_1604938863']) || (count($_SESSION['cart_dimmm931_1604938863']) == 0) )
		                <div class="col-sm-12 col-xs-12"> <br><br><br><center>
				        <h2> So far there is nothing to check=out  </h2>
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
				                $startSec = time(); //seconds 
				                $startMicroSec = microtime(true); //microseconds
		                        $i = 0;	
                                $totalSum = 0;
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
				                            <div class="visible-xs"><img class="img-mobile" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" /></div>
				                        </div> <!--name-->
			     
				                        <!--Dispalay image--> <!-- hidden in mobile -->
				                        <div class="col-sm-2 hidden-xs"> 
				                            <img class="my-one" src="{{URL::to("/")}}/images/ShopSimple/{{$inCartItems[$keyN]['shop_image'] }} "  alt="a" />
                                        </div>
				 
				                        <!-- Display Price -->
			                            <div class="col-sm-2 col-xs-4 word-breakX font-mobile"> 
                                            <span class="priceX">{{$inCartItems[$keyN]['shop_price']}} </span>  {{$inCartItems[$keyN]['shop_currency']}} 
                                        </div>
				 
				                        <!--Display quantity -->
		                                <div class="col-sm-2 col-xs-2 font-mobile"> <!-- // . $_SESSION['cart_dimmm931_1604938863'][$keyN]; //quantity-->
				                            <?php
				                            $quantityX = $_SESSION['cart_dimmm931_1604938863'][$key]; //gets the quantity
				                            ?>
					                        {{$quantityX}} <!-- Quantity -->
				                        </div>   <!--quantity-->	
				                        {{--END quantity div  --}}
				
				
				                        {{--Sum for one product--}}
			                            <div class="col-sm-2 col-xs-3 one-pr-sum font-mobile"> {{--total sum for this product, price*quantity--}}
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
	  
                            <!-- if user is not logged -->
                            @if (Auth::guest()) <!-- if user is not logged -->
                                <div class="col-sm-12 col-xs-12 shadowX"><hr>
	                                <h3> You are currently not logged </h3>
			                        <p> Please  <button class="btn  my-border"><a href="{{ route('login') }}">Login </a></button>  
			                            or    <button class="btn  my-border"><a href="{{ route('register') }}">Register </a></button> 
					                    if you want to keep history of your oders. Or proceed with 
					                    <!-- button to show form for unlogged user, works on bootstrap collapse -->
                                        <button class="btn my-border" data-toggle="collapse" data-target="#unloggedUserForm">one-click buy <i class="fa fa-check-square-o"></i></button> without logging  
			                        </p>
          
		                            <!-- Show Form for unlogged user (by Render Partial) (Form with user's details, i.e address, cell, etc)(Form is 90% like the same as for a logged user, but it does not have default user's values from DB + is display:none by default, hidden by Bootstrap class .collapse, in order to appear a user should click "Proceed with <button class="btn"><a href="#">one-click buy </a></button>) -->
			                        @include('ShopPaypalSimple.partial.checkOutView_unlogged_user_form', ['uuid' => $uuid ]) {{-- passing $uuid is not necessarily--}}
			                        <!-- Show Form for unlogged user (by Render Partial) -->
			  
		                        </div>	  
	  
	                        @else <!-- if user is logged -->
		                        <div class="col-sm-12 col-xs-12 shadowX"><hr>
		                            <p> Your Order ID is <b> {{$uuid}} </b> </p>
	                                <h3> You are currently  logged </h3>
			                        <p> Your account is <b> {{ auth()->user()->name }} </b> </p>
			                        <p> Email <b> {{ auth()->user()->email }} </b> </p>
                                </div> 
	  
                                <!-- Form with user's details, i.e address, cell, etc. Form is 90% like {ShopPaypalSimple.partial.checkOutView_unlogged_user_form} i.e (for a unlogged user), it does have default user's values from DB + IS NOT display:none by default, IS NOT hidden by Bootstrap class .collapse, in order to appear a user should click "Proceed with <button class="btn"><a href="#">one-click buy </a></button>)-->
	                            <div class="col-sm-12 col-xs-12 shadowX">
	                                <h2> Shipping details </h2>
	                                <form class="form-horizontal" method="post" class="form-assign" action="{{url('/payPage1')}}">
		                                <input type="hidden" value="{{csrf_token()}}" name="_token"/>
			  
			                            <!-- Name --> 
                                        <div class="form-group{{ $errors->has('u_name') ? ' has-error' : '' }}">
                                            <label for="u_name" class="col-md-4 control-label">Name</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="u_name" value="{{ old('u_name') ? old('u_name') : auth()->user()->name  }}" placeholder="Your name" required /> <!-- input value on load sets user DB name, but if input has old name (user printed some wrong name that failed validation and return the same page -->     
                                                @if ($errors->has('u_name'))
                                                    <span class="help-block"> <strong>{{ $errors->first('u_name') }}</strong> </span>
                                                @endif 
					                        </div>
                                        </div>			
              
			                            <!-- Email --> 
                                        <div class="form-group{{ $errors->has('u_email') ? ' has-error' : '' }}">
                                            <label for="u_email" class="col-md-4 control-label">E-mail</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="u_email" value="{{ old('u_email') ? old('u_email') : auth()->user()->email }}" placeholder="Your email" required />       
                                                @if ($errors->has('u_email'))
                                                    <span class="help-block"> <strong>{{ $errors->first('u_email') }}</strong> </span>
                                                @endif 
					                        </div>
                                        </div>
			   
			   
			                            <!-- Address --> 
                                        <div class="form-group{{ $errors->has('u_address') ? ' has-error' : '' }}">
                                            <label for="u_address" class="col-md-4 control-label">Address</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="u_address" value="{{ old('u_address') }}" placeholder="Your address" required />       
                                                @if ($errors->has('u_address'))
                                                    <span class="help-block"> <strong>{{ $errors->first('u_address') }}</strong> </span>
                                                @endif 
					                        </div>
                                        </div>
			   
			                            <!-- u_phone --> 
                                        <div class="form-group{{ $errors->has('u_phone') ? ' has-error' : '' }}">
                                            <label for="u_phone" class="col-md-4 control-label">Phone</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="u_phone" value="{{ old('u_phone') }}" placeholder="Your phone" required />       
                                                @if ($errors->has('u_phone'))
                                                    <span class="help-block"> <strong>{{ $errors->first('u_phone') }}</strong> </span>
                                                @endif 
					                        </div>
                                        </div>
			   
			   
                                        <!-- Other hidden fields/inputs -->
		                                <input type="hidden" name="u_uuid" value="{{ $uuid }}"  />      <!-- UUID-->
				                        <input type="hidden" name="u_sum"  value="{{ $totalSum }}" />   <!-- Sum -->
				                        <input type="hidden" name="u_items_in_order" value="{{ count($_SESSION['cart_dimmm931_1604938863']) }}" /> <!-- Sum -->
				                        <!-- Other hidden fields/inputs -->
			
				
                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary shadowX submitX rounded"> Done </button>
						                        <span class='small font-italic text-danger' ></span>
			                                </div>
				                        </div>
		      
		                            </form>
                                    <hr>
	                            </div>
	                            <!-- End Form with user's details, i.e address, cell, etc -->
                            @endif 
  
			
                            <div class="col-sm-12 col-xs-12" style="height:10em;"> <!-- just Spacing -->
	                            <!--<hr> key goes here...<?php //echo env('LIQPAY_PUBLIC_KEY', 'screw you, env key does not work'); ?>-->
	                        </div>
	               
	                    @endif
				
				    </div> <!-- end .shop -->
                </div> <!-- end .panel-default xo -->
            </div>
        </div>
</div> <!-- end . animate-bottom -->

@endsection