@extends('layouts.app')

<?php
//uses $_SESSION['cart_dimmm931_1604938863'] to store and retrieve user's cart;
?>

@section('content')
<!-- Lazy load is loaded in views/app.blade.php, otherwise it wont work!!!!!!!! -->

<!-- Include js/css file for this view only -->
<script src="{{ asset('js/ShopPaypalSimple/shopSimple_Loader.js')}}"></script> <!-- CSS Loader -->
<script src="{{ asset('js/ShopPaypalSimple/shopSimple.js')}}"></script>  
<script src="{{ asset('js/ShopPaypalSimple/my_LazyLoad_Shop_Simple.js')}}"></script>  <!--implement LazyLoad --> 

<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet">
<link href="{{ asset('css/ShopPaypalSimple/shopSimple_Loader.css') }}" rel="stylesheet">

<!-- Sweet Alerts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->

<!-- Include js file for this view only -->





<script>
  //passing php var to JS (for autocomplete.js)
  var productsX = {!! $allProductsSearchBar->toJson() !!};
</script>


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
					
					
                <div class="panel-heading text-warning row">
				
				  <div class="col-sm-3 col-xs-6">
				    Shop PayPal <span class="small text-danger">*</span> 
				  </div>
				  
				  <!--------  Select DropDown(select by category) (by Render Partial) --------->
				  <div class="col-sm-3 col-xs-6">Choose
					@include('ShopPaypalSimple.partial.dropdown', ['categ' => $allCategories])
				  </div>
					
				 <!--------  Select DropDown (filter by price)(by Render Partial) --------->
				  <div class="col-sm-3 col-xs-12">
				    @include('ShopPaypalSimple.partial.dropdownPrice')
				  </div>
				  
				</div>



                <div class="panel-body shop">
				
				    <div class="col-sm-7 col-xs-4">
                    <h1>Shop PayPal</h1>
		            </div>	
				
				    <!--------  Select DropDown (by Render Partial) --------->
				    <div class="col-sm-3 col-xs-6">
                        <!--@include('ShopPaypalSimple.partial.dropdown', ['categ' => $allCategories])-->
		            </div>
				    <!-------- End Select DropDown (by Render Partial) ----->
					
					
				
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
		
		
		
		            <!-------- Search bar (by Render Partial) --------->
                    @include('ShopPaypalSimple.partial.searchBar')
                    <!-------- End Search bar (by Render Partial) --------->
		
		
		
		
				    <!-------------------- Progress Status Icons by component ----------------->
	                <!--display shop timeline progress via Helper => Progress Status Icons-->
					<div class="col-sm-12 col-xs-12">
	                {!! \App\Http\MyHelpers\ShopSimple\ShopTimelineProgress2::showProgress2("Shop") !!}
					</div>
	                <!--------------  END  Progress Status Icons by component ----------------->
					  
					


                    




                   
                    				   
					
					<?php
					/*
					$allDBProducts = array(); // array to store formatted results from DB (from controller)
  
                    //getting results from DB to array in format => [ ['id'=>0, 'name'=> 'canon'], [], ]
                    //We get from DB an array of object {$allDBProducts} and here convert to array of arrays[$allDBProducts]. It is just another variation, we could use direct referring to array of object {$allDBProducts} like {$allDBProducts->l_name}, but in this case we have to rewrite the code below, as it was originally designed for array of arrays[$allDBProducts] (as firstly I created artificial hardcoded array of arrays[$allDBProducts]) 
                    foreach($allDBProducts as $a){ 
	                  $tempo = array();
	                  $tempo['id'] = $a->shop_id;
	                  $tempo['name'] =  $a->shop_title  ;//$a->l_name;//  ShopSimple::getLabel();
	                  $tempo['price'] = $a->shop_price;
					  $tempo['currency'] = $a->shop_currency;
	                  $tempo['image'] = $a->shop_image;
	                  $tempo['description'] = $a->shop_descr;
	  
	                  array_push($allDBProducts, $tempo ); //adds to final array
                    }
  
                    //var_dump($allDBProducts);
                    $_SESSION['productCatalogue'] = $allDBProducts; //all products from DB to session
					*/
                    ?>
  
  
  
                    <div class="row shop-items">
	                
					
					@if(count($allDBProducts) == 0)
						<div class="col-sm-12 col-xs-12">
					      <div class="col-sm-3 col-xs-3"></div><!-- just for centring-->
					      <div class="col-sm-6 col-xs-6 no-product">
						   <center>No products in DB <center>
						  </div>
						</div>
					@endif
					
					<!-- Count found products in DB -->
					<div class="col-sm-12 col-xs-12">
					    <i class="fa fa-delicious" style="font-size:1em; color: navy;"></i> 
						Found <span class="text-danger">{{count($countProducts)}} </span>items <p></p><p></p>
					</div>
	               
				   
		            <!--generate shop products, Loop ---------------------------------------------------------->
					@for ($i = 0; $i < count($allDBProducts); $i++)
					
				        <!-- Show One product with hidden modal (by Render Partial) -->
					    @include('ShopPaypalSimple.partial.oneProduct_with_hiddenModal', ['i' => $i, 'allDBProducts' => $allDBProducts ]) <?php //arg[0] - is an iterator (to use in loop or for single, arg[1] - is an object with data ) ?>
					    <!-- Shows One product with hidden modal (by Render Partial) -->
           
		            @endfor
		  
		  
		  
		  
		            <!---------- Display pagination links -------------->
		            <div class="col-sm-12 col-xs-12"> {{ $allDBProducts->links() }} </div>
		  
		  
	            </div> <!-- row shop-items -->
					













				
				</div> <!-- end .shop -->
				    
					
			
					
                
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->








@endsection
