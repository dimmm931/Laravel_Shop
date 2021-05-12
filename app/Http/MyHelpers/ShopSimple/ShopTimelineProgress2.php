<?php 

namespace App\Http\MyHelpers\ShopSimple;

class ShopTimelineProgress2
{
	
	
	
	/**
     * methods to display list of user's Rbac roles + Delete option, If 2nd param is TRUE. (Deletion uses <form>. Form submitted via Swal (my-rbac.js))
     * @param User $userModel to pass in loop or standalong
	 * @param boolean $buildDeleteButton (if true, additionally creates a POST form with button to detaching/deleting the role from user), if u pass no 2nd arg it is false by default 
     * @return string
     */
    public static function showProgress2($activeClass) {
	 ?>	 
	 
	<div class="row">
	
		  <center>
            <h3 class="shadowX widthX"><i class="fa fa-info-circle" style="font-size:0.8em"></i> Status </h3>
			<br>
			
			<div class="row items-list">
			
			     <!-- Shop icon -->
			    <div class="icon-item <?php echo ($activeClass == 'Shop' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->
			        <i class="fa fa-archive" style="font-size:24px;"></i>
					<p><a href="<?=route('shopSimple') ?>">Shop</a></p>
		        </div>
				      
				
				<!-- A line  -->
				<div class="icon-item">
				    <span class="line2">  </span>
			    </div>
				
				
			    <!-- Cart icon -->
			    <div class="icon-item <?php echo ($activeClass == 'Cart' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->
			        <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
				    <p><a href="<?=route('cart') ?>">Cart</a></p>
		        </div>
				
				<!-- A line  -->
				<div class="icon-item">
				    <span class="line2">  </span>
			    </div>
				
                <!-- Order icon -->				
				<div class="icon-item <?php echo ($activeClass == 'Order' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->	
				    <i class="fa fa-tablet" style="font-size:24px;"></i>
					<p><a href="<?=route('/checkOut2')?>">Order</a></p>
				</div>
				
				<!-- A line  -->
				<div class="icon-item">	
				    <span class="line2">  </span>
				</div>
				
                <!-- Payment icon -->				
				<div class="icon-item <?php echo ($activeClass == 'Payment' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->	
				    <i class="fa fa-cc-mastercard" style="font-size:24px;"></i>
					<p>Payment</p>
				</div>
				
				<!-- A line  -->
				<div class="icon-item">	
				    <span class="line2">  </span>
				</div>
				
                <div class="icon-item <?php echo ($activeClass == 'Complete' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->				
				    <i class="fa fa-check" style="font-size:24px;"></i>
					<p>Complete</p>
			    </div>
			
		
		    </div>
			
			
	  <!--<hr>-->
	  </center>
	</div>	
	
	<?php
	 }
	 
	 
	 
	 
	 
}