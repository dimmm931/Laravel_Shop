<?php
//Admin page to show one product by ID
?>

@extends('layouts.app')

@section('content')

<!-- Include js/css file for this view only -->
<link href="{{ asset('css/ShopPaypalSimple_AdminPanel/shopSimpleAdmin_view_products.css') }}" rel="stylesheet"> 

<div class="container">
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
					
                <div class="panel-heading text-warning col-sm-12 col-xs-12">
					<!-- Link to go back,  -->
				    <div class="col-sm-8 col-xs-8">
					    <h3>
						    <i class="fa fa-address-card-o border shadowX" style="font-size:36px; margin-right: 0.2em;"></i> 
							<div class="visible-xs small" style="margin-top:0.2em;">One product</div> <!-- visible for mobile only-->
							<span class="hidden-xs">One product<span class="small text-danger">*</span></span> <!-- visible for Desktop only-->
						</h3>  
				        <p>&nbsp;<i class="fa fa-hand-o-left" style="font-size:24px"></i>
				            <a href="{{ url('/admin-products') }}">back to View all products </a>
						</p>
                    </div>
					
					<!--- Start of Dropdown to switch between shop categories i.e "desktop/mobile". Built on SQL query to table {shop_categories} . Works on Bootstrap dropdown   -->
					<div class="col-sm-4 col-xs-4">
					    <div class="dropdown">
                            <i class="fa fa-chevron-down dropdown-toggle" data-toggle="dropdown"></i>   
                            Category
                            <div class="dropdown-menu ">                                        
	                            <ul>
							        <li> <a class="dropdown-item" href={{ url("/admin-orders") }}>  All stuff  {!! (!isset($_GET['admin-product-category']))  ? ' <span class="text-danger">&hearts;</span>' : ' ' !!} </a></li> <!-- html unescapped tags / without escapping-->
                              </ul>
                            </div>
                        </div>
					</div>
					<!--- End Dropdown to select between "proceeded"/"non-proceeded" -->

				</div>

				<!-- Just info, may delete later -->
				<div class="col-sm-12 col-xs-12 alert alert-info small font-italic text-danger  shadowX" style="margin-top:1em;">
					</br> Some notes here.....
				</div>
				
                <div class="panel-body shop">

				    <!-- If no orders in DB --> 
		            @if(count($productOne) == 0)
					    <div class="col-sm-12 col-xs-12"><center><h4 class="text-danger"><i class="fa fa-calendar-check-o" style="font-size:24px"></i> 
							No products so far</center></h4>
						</div>
					@else

					    <!--------- Display products  --------------->
                        <div class="col-sm-12 col-xs-12 admin-one-product">
					    
						    <!-- image -->
					        <div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    @if(file_exists(public_path('images/ShopSimple/' . $productOne[0]->shop_image)))
						        <img class="admin-one-img" src="{{URL::to("/")}}/images/ShopSimple/{{$productOne[0]->shop_image }}"  alt="product"/>
						    @else
							    <img class="admin-one-img" src="{{URL::to("/")}}/images/ShopSimple/no-image.png"  alt="product"/>
							@endif
						</div>
						
						<!-- name -->
					    <div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    {{ $productOne[0]->shop_title }}
						</div>
						
						<!-- description -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    {{ $productOne[0]->shop_descr }}
						</div>
						
						<!-- price -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    Price: {{ $productOne[0]->shop_price }}  {{ $productOne[0]->shop_currency }}
						</div>
						
						<!-- Category -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    Category: {{ $productOne[0]->categoryName->categ_name }} <!--hasOne(NOT hasMany) relation in '/model/ShopSimple' on table {shop_categories} -->
						</div>
						
						<!-- Device type -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    Device type: {{ $productOne[0]->sh_device_type }}
						</div>
						
						<!-- Quantity All-->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    Initial Quantity in stock:<b> {{ $productOne[0]->quantityGet->all_quantity }} </b>items <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
						</div>
						
						<!-- Quantity Left -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    Quantity left:<b> {{ $productOne[0]->quantityGet->left_quantity }} </b>items <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
						    @if($productOne[0]->quantityGet->left_updated != null)
							    <p class="small font-italic text-danger">(last purchase: {{ $productOne[0]->quantityGet->left_updated }})</p>  <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
							@else
								<p class="small font-italic text-danger">(last purchase: was never purchased)</p>
						    @endif
						</div>
			
			            <!-- Edit button -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    <button><a href="{{   url('/admin-edit-product')}}/{{$productOne[0]->shop_id }}" > <span onclick="return confirm('Are you sure to edit?')">Edit via/GET  <img class="deletee"  src="{{URL::to("/")}}/images/edit.png"  alt="edit"/></span></a></button>		
						</div>
						
						 <!-- Delete button -->
						<div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
						    <form class="detach" method="post" action="{{url("/admin-delete-product")}}" >
                                <input type="hidden" value="{{csrf_token()}}" name="_token" />
	                            <input type="hidden" value="{{$productOne[0]->shop_id}}" name="prod_id" />
	                            <button  onclick="return confirm('Are you sure to Delete?')" type="submit" class="detach-role"> 
									Delete via /Post <i class="fa fa-trash-o" style="cursor:pointer;color:red; font-size:1.6em;"></i> 
							    </button> 
                            </form>
						</div>
					</div>  <!-- end .admin-one-product-->
					<!--------- End Display products  --------------->	

			    @endif
                    					
			    </div> <!-- end .shop -->
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->

<script>
</script>

@endsection