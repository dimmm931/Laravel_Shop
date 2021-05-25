<?php 
namespace App\Http\MyHelpers\ShopSimple;

class ShopTimelineProgress2
{
	/**
     * Methods to display ShopTimeline
     * @param $activeClass to highlight active menu point
     * @return string
     *
     */
    public static function showProgress2($activeClass) 
    {
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
					    <p><a href="<?=route('/payPage2')?>">Payment</a></p>
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