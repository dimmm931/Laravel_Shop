<?php
//Admin page to show all shop orders in table
?>

@extends('layouts.app')

@section('content')

<!-- Include js/css file for this view only -->
<link href="{{ asset('css/ShopPaypalSimple/shopSimple.css') }}" rel="stylesheet"> 
<link href="{{ asset('css/ShopPaypalSimple_AdminPanel/shopSimpleAdmin_view_orders.css') }}" rel="stylesheet"> 
<!-- Sweet Alerts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> <!-- Sweet Alert CSS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script> <!--Sweet Alert JS-->
<!-- Include js file for thisview only -->
<script src="{{ asset('js/ShopPaypalSimple_Admin/ViewOrders/adminOrders.js')}}"></script> <!-- Orders JS -->

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
					 
						<h3>
						    <i class="fa fa-truck border shadowX" style="font-size:36px; margin-right: 0.2em;"></i> 
							<div class="visible-xs small" style="margin-top:0.2em;">Admin orders</div> <!-- visible for mobile only-->
							<span class="hidden-xs">All shop orders <span class="small text-danger">*</span></span> <!-- visible for Desktop only-->
						</h3> 
						
				        &nbsp;<i class="fa fa-arrow-circle-o-left" style="font-size:24px"></i>
				        <a href="{{ url('/shopAdminPanel') }}">back to admin panel </a>
                    </div>
					
					
					<!--- Dropdown to select between "proceeded"/"non-proceeded/shipped". Works on Bootstrap dropdown   -->
					<div class="col-sm-4 col-xs-6">
					    <div class="dropdown">
						    <!-- display Text based on $_GET['admOrderStatus'] using ternary, if no S_GET in URL, display "Not-proceed", else display S_GET text and make 1st letter capital -->
						    {{ (!isset($_GET['admOrderStatus'])) ? 'Not-proceed (' . $countOrders->count() . ')'  : ucfirst($_GET['admOrderStatus']) . ' (' . $countOrders->count() . ')' }}
                             <i class="fa fa-chevron-down dropdown-toggle" data-toggle="dropdown"></i>   
 
                            <div class="dropdown-menu ">                                        
	                            <ul>
	                                <!-- for every <li> check if URL contains ($_GET['admOrderStatus']) and if $_GET['admOrderStatus']=='item' and in that case make that <li> invisible  -->
                                    <li class="{{ (!isset($_GET['admOrderStatus'])) ? 'hidden':''}}"> <a class="dropdown-item" href={{ url("/admin-orders") }}> Not proceeded ({{ $countNotProceeded }}) </a></li>  <!-- e.g Not proceeded (12) -->
								    <li class="{{ (isset($_GET['admOrderStatus']) && $_GET['admOrderStatus'] == 'proceeded') ? 'hidden':''}}"> <a class="dropdown-item" href={{ url("/admin-orders?admOrderStatus=proceeded") }} > Proceeded ({{ $countProceeded }}) </a></li> 
                                    <li class="{{ (isset($_GET['admOrderStatus']) && $_GET['admOrderStatus'] == 'delivered') ? 'hidden':''}}"><a class="dropdown-item" href={{ url("/admin-orders?admOrderStatus=delivered") }} > Delivered  ({{ $countDelivered }})  </a></li> 
                                </ul>
                            </div>
                        </div>
					</div>
					<!--- End Dropdown to select between "proceeded"/"non-proceeded" -->
				
				</div>
				
				<!-- Just info, may delete later -->
				<div class="col-sm-12 col-xs-12 alert alert-info small font-italic text-danger shadowX">
				    Now the script selects all stuff by {ShopOrdersMain::where('ord_status', 'not-proceeded')->get();}
					</br> In future it must additionally select ->where('if_paid', 1) if your shop is intendented to work with on-line payment only (no payment on delivery)
				</div>
				
				

                <div class="panel-body shop">
				
				    <div class="col-sm-10 col-xs-10">
                        <h1>Orders</h1>
		            </div>	
				
				    <!-- If no orders in DB --> 
		            @if(count($shop_orders_main) == 0)
                        
					    <div class="col-sm-12 col-xs-12"><center><h4 class="text-danger"><i class="fa fa-calendar-check-o" style="font-size:24px"></i> 
					        <!-- Below I.e =>  No new not-proceeded orders so far 0 -->
							No {{(isset($_GET['admOrderStatus'])) ? $_GET['admOrderStatus'] : ' new not-proceeded '}} orders so far</center></h4>
						</div>
					@else
						 
					    <!-- Display orders -->
                        <div class="col-sm-12 col-xs-12 fit-content">
					
					        <!-- THEAD -->
		                    <div class="col-sm-12 col-xs-12  list-group-item">
		                        <div class="col-sm-2 col-xs-6">UUID</div>
						        <div class="col-sm-2 col-xs-6">Status</div>
			                    <div class="col-sm-1 col-xs-6">Sum</div>
			                    <div class="col-sm-1 col-xs-6">Quant</div>
						        <div class="col-sm-2 col-xs-6">Items</div>
			                    <div class="col-sm-1 col-xs-6">Name</div>
						        <div class="col-sm-1 col-xs-6">Date</div>
						        <div class="col-sm-1 col-xs-6">Paid</div>
						        <div class="col-sm-1 col-xs-6">User</div>
		                    </div>
		                <!-- End THEAD -->
					
					
					    <?php $i = 0; ?>
					    @foreach($shop_orders_main as $v)
					        <div class="col-sm-12 col-xs-12  list-group-item">
					  
					            <!----- Order UUID ------>
						        <div class="col-sm-2 col-xs-12 "><i class="fa fa-calendar-check-o" style="font-size:24px"></i> {{ $v-> ord_uuid}}</div>
						
						        <!-- Status: proceeded/not-proceeded/delivered and <form> to change the status -->
						        <div class="col-sm-2 col-xs-12 ">
						            {!! ($v->ord_status=='not-proceeded')? "<span class='text-danger'>Not proceeded</span>" : "<span class='text-success'>Proceeded</span>" !!} </br> <!-- Blade without escaping htmlentities()  -->
						     
							        <!-- Form to change/updaye status -->
							        <form class="form-horizontal" method="post" class="form-assign" action="{{url('/updateStatus')}}">
		                                <input type="hidden" value="{{csrf_token()}}" name="_token"/>
			  
			                            <!-- Form input to change Status. Uses <Select><option>  --> 
                                        <div class="form-group{{ $errors->has('u_status') ? ' has-error' : '' }}">
                                    
                                            <div class="col-md-6">
									            <select class="mdb-select md-form form-control" name="u_status">
                                                    <option value='not-proceeded' {{ $v->ord_status=='not-proceeded' ?  "selected='selected'" : '' }}> Not proceeded </option> <!-- check if make this option selected='selected -->
											        <option value='proceeded'     {{ $v->ord_status=='proceeded'     ?  "selected='selected'" : '' }} > Proceeded    </option>
											        <option value='delivered'     {{ $v->ord_status=='delivered'     ?  "selected='selected'" : '' }} > Delivered    </option>
										        </select>
										
                                                @if ($errors->has('u_status'))
                                                    <span class="help-block"> <strong>{{ $errors->first('u_status') }}</strong> </span>
                                                @endif 
					                        </div>
                                        </div>
								        <!-- End Form input to change Status. Uses <Select><option>  -->

                                        <!-- Other hidden fields/inputs -->
		                                <input type="hidden" name="u_orderID" value="{{ $v->order_id }}"  />   <!-- Order ID-->
				
                                        <div class="form-group">
                                            <div class="col-md-8 ">
                                                <button type="submit" class="btn btn-primary shadowX submitX rounded change-status-click" data-prev-status="{{$v->ord_status}}" > Done </button>
			                                </div>
				                        </div>
		     
		                            </form>								
								
						        </div>
						        <!-- End Status: proceeded/not-proceeded/delivered and <form> to change the status -->
						

						
						        <!-- Sum -->
						        <div class="col-sm-1 col-xs-12 "><span class="visible-inline-xs">Sum:   </span> {{ $v-> ord_sum}} ₴ </div>       <!-- .visible-xs visible in mobile only, .visible-inline-xs is used to display in same line not next -->
						        <div class="col-sm-1 col-xs-12 "><span class="visible-inline-xs">Items: </span> {{ $v-> items_in_order}}</div>  <!-- .visible-xs visible in mobile only, .visible-inline-xs is used to display in same line not next -->						
						
						        <!--  Order details from table {shop_order_item} i.e  HP notebook 2 pcs * 35.31 ₴. Uses hasMany.-->
						        <div class="col-sm-2 col-xs-12" style="font-size:0.8em;"> 
                                    <!-- Start hasMany via ass. Working!!! Currently commented in view and reassigned to hasMany -->							
						            <!-- End hasMany via ass. Working!!! Currently commented in view and reassigned to hasMany -->

						            <!-- additionall check (in case u saved order to table {shop_orders_main} but saving to table {shop_order_item} failed and therefore table {shop_order_item} does not have related/corresponded column to  {shop_orders_main}) -->
						            @if( $v->orderDetail->isEmpty() ) <!-- hasMany relation, model {ShopOrdersMain} connects by ID to model {ShopOrdersItems} -->
								        corrupted data
							        @else
						   
						                <!-- hasMany realtionShip, Working!!!!. Mega Error: hasMany must be inside second foreach -->
						                @foreach ($v->orderDetail as $x)
						                    <div class="border">
							                    <p><i class="fa fa-paperclip"></i> {{$x->productName2->shop_title}} </p> <!--hasOne function {productName} from model {ShopOrdersItems} connects by id to model {ShopSimple} -->
							                    <p> {{$x->items_quantity}} pcs  * {{$x->item_price}} ₴ = {{ $x->items_quantity * $x->item_price }} ₴ </p> {{-- quantity * price = sum  --}}
							                </div>		  
						                @endforeach
						                <!-- hasMany realtionShip, Working!!!! -->
						            @endif
			 
						        </div>  <!-- hasMany. Order details from table {shop_order_item}-->
						
                                <?php $i++; ?>
						
						        <!-- Buyer details, address, phone, etc -->
						        <div class="col-sm-1 col-xs-12 "> {{ $v-> ord_name }}</br> {{ $v-> ord_address }}</br> {{ $v-> ord_email }} </br> {{ $v-> ord_phone }}</div>
					            <!-- Date, teime when order was placed -->
						        <div class="col-sm-1 col-xs-12 "> {{ $v-> ord_placed}}</div>
						        <div class="col-sm-1 col-xs-12 "> {!! ($v-> if_paid==0)? "<span class='text-danger'>Not paid</span>" : "<span class='text-success'>Paid</span>" !!}</div> <!-- Blade without escaping htmlentities()  -->
					            <div class="col-sm-1 col-xs-12 "> <span class="visible-inline-xs">UserID: </span> {{ $v->ord_user_id }}</div> <!-- .visible-xs visible in mobile only, .visible-inline-xs is used to display in same line not next -->
					            </div>
					    @endforeach
					</div>
					
					
					<!-- Pagination -->
					<div class="col-sm-12 col-xs-12 ">
					    {{ $shop_orders_main->links() }}
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