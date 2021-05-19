<?php
//Admin page to show all shop products
?>

@extends('layouts.app')

@section('content')

<!-- Include js/css file for this view only -->
<link href="{{ asset('css/ShopPaypalSimple_AdminPanel/shopSimpleAdmin_view_products.css') }}" rel="stylesheet"> 
<!-- Autocomplete is in views/layout/ -->
<!-- End Include js/css file for this view only -->

<script>
  //passing php var to JS (for autocomplete.js)
  var productsX = {!! $allProductsSearchBar->toJson() !!};
</script>

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
					
					
                <div class="panel-heading text-warning col-sm-12 col-xs-12 headering-x">
					<!-- Link to go back,  -->
				    <div class="col-sm-8 col-xs-8">
					    <h3>
						    <i class="fa fa-address-card-o border shadowX" style="font-size:36px; margin-right: 0.2em;"></i> 
							<div class="visible-xs small" style="margin-top:0.2em;">Shop products</div> <!-- visible for mobile only-->
							<span class="hidden-xs">All shop products <span class="small text-danger">*</span></span> <!-- visible for Desktop only-->
						</h3>  

						<p>&nbsp;<i class="fa fa-hand-o-left" style="font-size:24px"></i>
				            <a href="{{ url('/shopAdminPanel') }}">back to admin panel </a>
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
							        @foreach($allCategories as $category)
								        <li> <a class="dropdown-item" href={{ url("/admin-orders") }}>  {{ $category->categ_name}}  {!! (isset($_GET['admin-product-category']) && $_GET['admin-product-category'] == $category->categ_name )  ? ' <span class="text-danger">&hearts;</span>' : ' ' !!} </a></li> <!-- html unescapped tags / without escapping-->
								    @endforeach
                                </ul>
                            </div>
                        </div>
					</div>
					<!--- End Dropdown to select between "proceeded"/"non-proceeded" -->
				</div> <!-- End .headering-x -->
				
                
				<!-------- Search bar (by Render Partial) ------------->
                    @include('ShopPaypalSimple.partial.searchBar')
                <!-------- End Search bar (by Render Partial) --------->
					
                    
				<!-- Just info, may delete later -->
				<div class="col-sm-12 col-xs-12 alert alert-info small font-italic text-danger  shadowX" style="margin-top:1em;">
					</br> Some notes here.....
					</br> Please Do edit and delete only products you have created by yourself via form, not the ones created via Seeder. 
				</div>
				

                <div class="panel-body shop">
				    <div class="col-sm-10 col-xs-10 add-new">
                        <!-- <h1>All shop stuff</h1>-->
						<!-- Add new button -->
						<div class='col-sm-2 col-xs-5 subfolder shadowX icon-my' style="margin-bottom:1em;">
					        <div class="inside">
						        <a href="{{ route('admin-add-product') }}">  
						            <i class="fa fa-plus-circle " style="font-size:46px"></i> 
							        <p> Add new</p><br>  
						        </a>
		                    </div>
						</div>
		            </div>	<!-- end .add-new -->
				
                
				    <!-- If no orders in DB --> 
		            @if(count($allProducts) == 0)
					    <div class="col-sm-12 col-xs-12"><center><h4 class="text-danger"><i class="fa fa-calendar-check-o" style="font-size:24px"></i> 
							No products so far</center></h4>
						</div>
					@else
						 
					    <!--------- Display products  --------------->
                        <div class="col-sm-12 col-xs-12 admin-orders">
					        @foreach($allProducts as $oneProduct)
						        <div class="col-sm-12 col-xs-12  list-group-item bg-success cursorX shadowX">
							        <div class="col-sm-2 col-xs-12">
							            {{ $oneProduct->shop_title }} <!-- product Name --> 
							        </div>
								
								    <div class="col-sm-2 col-xs-12">
							            {{ $oneProduct->categoryName->categ_name }} <!-- Category. <!--hasOne(NOT hasMany) relation in '/model/ShopSimple' on table {shop_categories} -->
							        </div>
								
								    <div class="col-sm-2 col-xs-12">
							            {{ $oneProduct->shop_price  }} {{ $oneProduct->shop_currency  }}  <!-- 1121 $ --> 
							        </div>
								
								    <div class="col-sm-2 col-xs-12"> <!-- View  Button --> 
							           <button><a href = 'admin-one-product/{{ $oneProduct->shop_id }}'>  <span>View  <i class="fa fa-eye" style="cursor:pointer;"></i> </span></a></button>  
							        </div>
								
								    <div class="col-sm-2 col-xs-12"> <!-- Edit Button --> 
							           <button><a href = 'admin-edit-product/{{ $oneProduct->shop_id }}'>  <span onclick="return confirm('Are you sure to edit?')">Edit via /GET  <img class="deletee"  src="{{URL::to("/")}}/images/edit.png"  alt="edit"/></span></a></button>  
							        </div> 
                                
								    <div class="col-sm-2 col-xs-12"> <!-- Delete Button --> 							    
								
								        <form class="detach" method="post" action="{{url("/admin-delete-product")}}" >
                                            <input type="hidden" value="{{csrf_token()}}" name="_token" />
	                                        <input type="hidden" value="{{$oneProduct->shop_id}}" name="prod_id" />
	                                        <button  onclick="return confirm('Are you sure to Delete?')" type="submit" class="detach-role"> 
									            Delete via /Post <i class="fa fa-trash-o" style="cursor:pointer;color:red; font-size:1.6em;"></i> 
									        </button> 
                                        </form>
								    </div>    
							    </div>
						    @endforeach
					    </div>  <!-- end .admin-orders-->
					    <!--------- End Display products  --------------->	
                       
					
					    <!-- Pagination -->
					    <div class="col-sm-12 col-xs-12 ">
					        {{ $allProducts->links() }}
					    </div>
					    <!-- Pagination -->
					
				    @endif
                    					
				</div> <!-- end .shop -->
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->

<script>
</script>

@endsection