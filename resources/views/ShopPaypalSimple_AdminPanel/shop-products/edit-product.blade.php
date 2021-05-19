<?php
//Admin page to add a product
?>

@extends('layouts.app')

@section('content')

<!-- Include js/css file for this view only + SEE ONE JS AT THE BOTTOM -->
<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet">
<link href="{{ asset('css/ShopPaypalSimple_AdminPanel/product_tabs.css') }}" rel="stylesheet"> <!-- Css for W3school Full Page Tabs (uses css + js) https://www.w3schools.com/howto/howto_js_full_page_tabs.asp  -->
<!-- Sweet Alerts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->
<!-- Include js file for this view only -->

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
				  
					<!-- Link to go back -->
				    <div class="col-sm-8 col-xs-6">
					    <p>  
						    <i class="fa fa-paperclip border shadowX" style="font-size:46px; margin-right: 0.5em;"></i>  
							Edit  <span class="small text-danger">*</span> 
							<br>  
						</p>
				        
						&nbsp;<i class="fa fa-arrow-circle-o-left" style="font-size:24px"></i>&nbsp;       <a href="{{ url('/admin-products') }}">back to View all products </a><br>
				        &nbsp;<i class="fa fa-arrow-circle-o-left" style="font-size:24px"></i>&nbsp;&nbsp;<a href="{{ url('/shopAdminPanel') }}">back to admin panel </a>
                    </div>
					

					<!--- Start of Dropdown to switch between shop categories i.e "desktop/mobile". Built on SQL query to table {shop_categories} . Works on Bootstrap dropdown   -->
					<div class="col-sm-4 col-xs-6">
					    Some right stuff
					</div>
					<!--- End Dropdown to select between "proceeded"/"non-proceeded" -->

				</div>
				
				
				<!-- Just info, may delete later -->
				<div class="col-sm-12 col-xs-12 alert alert-info small font-italic text-danger  shadowX">
					</br> Some notes here.....
				</div>

                <div class="panel-body shop">
				
				    <div class="col-sm-10 col-xs-10">
                        <h1>Edit an item</h1>
		            </div>	
					
					<!-- Here displays page: edit product, edit quantity. Shown via W3school Tabs -->
				    <div class="col-sm-12 col-xs-12 admin-add-new-item">
					    <!-- Start W3school Full Page Tabs (uses css + js file + js <button onclick="openPage()") https://www.w3schools.com/howto/howto_js_full_page_tabs.asp  -->
					    <button class="tablink" onclick="openPage('Home', this, '#d4d4f7')" id="defaultOpen">Edit product</button>
                        <button class="tablink" onclick="openPage('EditQuantity', this, '#eaeafb')">Load Stock</button>
                        <button class="tablink" onclick="openPage('About', this, 'orange')">Info</button>

                        <!--------  1st tab div (with edit product form) --------------> 
                        <div id="Home" class="tabcontent">
                            <!--------- Form to a add new item   --------------->
				            <form class="form-horizontal" method="post" action="{{url('/storeNewproduct')}}" enctype="multipart/form-data">
                                <input type="hidden" value="{{csrf_token()}}" name="_token" /><!-- csrf-->
 
 
                                <!-- product name -->
                                <div class="form-group{{ $errors->has('product-name') ? ' has-error' : '' }}">
                                    <label for="product-name" class="col-md-4 control-label">Product name</label>
                                    <div class="col-md-6">
                                        <input id="product-name" type="text" class="form-control" name="product-name" value="{{old('product-name', $productOne[0]->shop_title)}}" required autofocus>
                                        @if ($errors->has('product-name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-name') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	    
                           						   
						        <!-- product description -->
                                <div class="form-group{{ $errors->has('product-desr') ? ' has-error' : '' }}">
                                    <label for="product-desr" class="col-md-4 control-label">Description</label>
                                    <div class="col-md-6">
                                        <textarea cols="5" rows="5" id="product-desr"  class="form-control" name="product-desr" required> {{old('product-desr', $productOne[0]->shop_descr)}} </textarea>
                                        @if ($errors->has('product-desr'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-desr') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	 
							
							
							    <!-- product price -->
                                <div class="form-group{{ $errors->has('product-price') ? ' has-error' : '' }}">
                                    <label for="product-price" class="col-md-4 control-label">Price</label>
                                    <div class="col-md-6">
                                        <input id="product-price" type="number" step="0.01" class="form-control" name="product-price" value="{{ old('product-price') }}" required>
                                        @if ($errors->has('product-price'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-price') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	 
							
							    <!-- product category Select dropdown  -->
                                <div class="form-group{{ $errors->has('product-category') ? ' has-error' : '' }}">
                                    <label for="product-category" class="col-md-4 control-label">Category</label>
                                    <div class="col-md-6">
                                        <select name="product-category" class="mdb-select md-form">
						                    <option  disabled="disabled"  selected="selected">Choose category</option>
						                </select>

                                        @if ($errors->has('product-category'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-category') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	
							
							
							    <!----- Image  ------->
					            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label for="image" class="col-md-4 control-label">Image <span class='small font-italic text-danger'> (must be .jpeg, .png, .jpg, .gif, .svg file. Max 2048)</span></label>
                                    <div class="col-md-6">
                                        <input type="file" name="image" class="form-control">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	
							
							    <!-- Button --> 
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary"> Create </button>
                                    </div>
                                </div>
                            </form>
						    <!--------- End Form to a add new item   --------------->    
                        </div>
                        <!------------------ End 1st tab div (with edit product form) -------------->
                    
	
					
					
					    <!-------------------- 2nd tab div (with edit quantity form) ---------------------------------->
                        <div id="EditQuantity" class="tabcontent">
                            <h3>Edit quantity</h3>
                            <p>Load to stock new quantity of : <span style="color:red; font-weight:bold;"> {{ $productOne[0]->shop_title }} </b></p>
							<p> 
							    All quantity:  {{ $productOne[0]->quantityGet->all_quantity }} items <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
							    Last load:     {{ $productOne[0]->quantityGet->all_updated }}        <!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->
							</p>
							<p> Currently left: {{ $productOne[0]->quantityGet->left_quantity }} items</p><!--hasOne relation in '/model/ShopSimple' on table {shop_quantity} -->


							<!--------- Form to a edit a quntity ++   --------------->                            
							<div class="col-sm-12 col-xs-12"> 
							    <center>
								    <h4><i class="fa fa-plus-square-o" ></i> Load in ++ (add to {{ $productOne[0]->quantityGet->all_quantity }} items ) </h4>
								</center>
							</div>
							
				            <form class="form-horizontal" method="post" action="{{url('/addQuantity')}}" enctype="multipart/form-data">
                                <input type="hidden" value="{{csrf_token()}}" name="_token" /><!-- csrf -->
								<input type="hidden" value="{{ $productOne[0]->shop_id }}" name="prod-id" /> <!-- product ID -->
                                                               
							    <!-- product quantity (to add to table {}) -->
                                <div class="form-group{{ $errors->has('product-quant') ? ' has-error' : '' }}">
                                    <label for="product-quant" class="col-md-4 control-label">Quantity++</label>
                                    <div class="col-md-6">
                                        <input id="product-quant" type="number" min="0" class="form-control" name="product-quant" value="{{ old('product-quant') }}" required>
                                        @if ($errors->has('product-quant'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-quant') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	
							
							    <!-- Button --> 
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary"> Add  <i class="fa fa-plus-square-o" ></i></button>
                                    </div>
                                </div>
                            </form>
						    <!--------- End Form to edit quantity ++  --------------->  

						
						    <br>
						    <!--------- Form to a edit a quntity Minus --   --------------->
						    <div class="col-sm-12 col-xs-12"> 
							    <center>
								    <h4><i class="fa fa-minus-square-o" ></i> Load out -- </h4>
								</center>
							</div>
							
				            <form class="form-horizontal" method="post" action="{{url('/minusQuantity')}}" enctype="multipart/form-data">
                                <input type="hidden" value="{{csrf_token()}}" name="_token" /><!-- csrf-->
							    <input type="hidden" value="{{ $productOne[0]->shop_id}}" name="prod-id" /><!-- product ID -->

							    <!-- product quantity (to add to table {}) -->
                                <div class="form-group{{ $errors->has('product-quant') ? ' has-error' : '' }}">
                                    <label for="product-quant" class="col-md-4 control-label">Quantity--</label>
                                    <div class="col-md-6">
                                        <input id="product-quant" type="number" min="0" class="form-control" name="product-quant" value="{{ old('product-quant') }}" required>
                                        @if ($errors->has('product-quant'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('product-quant') }}</strong>
                                            </span>
                                        @endif 
							        </div>
                                </div>	
							
							    <!-- Minus submit Button --> 
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary"> Minus  <i class="fa fa-minus-square-o" ></i></button>
                                    </div>
                                </div>
							
                            </form>
						    <!--------- End Form to edit quantity --  --------------->  
                        </div>
						<!------------------------ End 2nd tab div (with edit quantity form) ----------------->
  
  
                        <!-------- 3rd tab div ------->
                        <div id="About" class="tabcontent">
                            <h3>Info</h3>
                            <p>At those tabs you can edit products and their quantity.</p>
                        </div>
						<!-------- End 3rd tab div ------->
			
					</div>  <!-- end .admin-add-new-item -->
					<!--------- End  Here displays page: edit product, edit quantity. Shown via Tabs -->
				
				</div> <!-- end .shop -->   
            </div> <!-- end .panel-default xo -->
        </div>
    </div>
</div> <!-- end . animate-bottom -->

<!-- Include js/css file for this view only -->
 <script src="{{ asset('js/ShopPaypalSimple_Admin/Products/product_tabs.js') }}"></script> <!--  JS for W3school Full Page Tabs (uses css + js) https://www.w3schools.com/howto/howto_js_full_page_tabs.asp  -->
<!-- Include js/css file for this view only -->

@endsection