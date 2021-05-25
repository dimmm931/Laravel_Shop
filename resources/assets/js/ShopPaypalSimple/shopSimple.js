//Js for front SHOP {Simple}, (main) part of the shop (page with products)
(function(){ //START IIFE (Immediately Invoked Function Expression)
$(document).ready(function(){
	

    /*
    |--------------------------------------------------------------------------
    | Shop products/items category Dropdown. On change redirect to <option> href
    |--------------------------------------------------------------------------
    |
    |
    */   
    
	if(document.getElementById("dropdowShop") !== null){ //additional check to avoid errors in console in actions, other than actionShowAllBlogs(), when this id does not exist
	    document.getElementById("dropdowShop").onchange = function() {
            window.location.href = this.value;
        };
	}
	

	
	
    /*
    |--------------------------------------------------------------------------
    | On opening a modal pop-up (when u click a product), do reset any changes if they were applied early (e.g <button> css was changed)
    |--------------------------------------------------------------------------
    |
    |
    */
    
    $('.modal-trigger').on('click', function(){ 
		normalizeAddToCartButton(this);
	});
	 
	 
	 
	 
	
	 
    //[ +/- num product ]*/
	    	
    /*
    |--------------------------------------------------------------------------
    | Plus ++
    |--------------------------------------------------------------------------
    |
    |
    */
    
    $('.button-plus').on('click', function(){ 
	    //in case product was in cart, user minus-- it, till the last, and button was changed to "Remove from cart?"
		if ($('.submitX').val() != 'Add to cart'){
			$('.submitX').val('Add to cart');
			$('.submitX').css('background', '#717fe0');
		}
		
		var numProduct = Number($(this).closest('div').next().find('input:eq(1)').val()); //gets current input quantity. Use {input:eq(1)}, as {input:eq(0)} is a CSRF token
		
		//check a case if a user clicks button "+" more times than items are available (Db table{}) 
		var leftItems = this.getAttribute("data-quantLeft"); //gets qunatity from {data-quantLeft} on button "+"
		
		if(numProduct >= leftItems){
			swal("Stop!", "You took all the items in stock. No more items are available for now.", "warning");
			return false;
		}
		//end check a case if a user clicks button "+" more times than items are available (Db table{}) 
		
		$(this).closest('div').next().find('input:eq(1)').val(Number(numProduct) + 1); //html new value++
		
		var price    = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets DB currency from {data-currX} 
		calcPrice(numProduct+1, price, currency);
    });
	
	 

	 
	  
	 
    /*
    |--------------------------------------------------------------------------
    | Minus --
    |--------------------------------------------------------------------------
    |
    |
    */

    $('.button-minus').on('click', function(){
		var numProduct = Number($(this).closest('div').prev().find('input:eq(1)').val()); //gets current input quantity. Use {input:eq(1)}, as {input:eq(0)} is a CSRF token
		
		if(numProduct <= 1){
			if(this.getAttribute("data-cartFlag")=="true"){ //if product was prev selected and now is in cartFlag
			    $('.submitX').val('Remove from cart?'); 
				$('.submitX').css('background', 'red'); //change button style 
			} else {
			    swal("Stop!", "Can't select zero items", "warning");
			    return false;
			}
		}
		
		if(numProduct == 0){
			swal("Stop!", "Can't go further", "warning");
			return false;
		}
		
		
		$(this).closest('div').prev().find('input:eq(1)').val(numProduct - 1); //html new value--
		var price    = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets DB currency from {data-currX}
		calcPrice((numProduct-1), price, currency); //quantity*price
    });
	
	
	      
	 
    /*
    |--------------------------------------------------------------------------
    | Calcs & html the amount of sum for all items, i,e 2x16.66 = N
    |--------------------------------------------------------------------------
    |
    |
    */
    
	function calcPrice(quant, itemPrice, currencyArg){
		//$('.sum').html(Number(quant) + ' item x ' + itemPrice + '₴ = ' + (quant*itemPrice).toFixed(2) + '₴' );
		$('.sum').stop().hide(100,function(){ $(this).html( Number(quant) + ' item x ' + itemPrice + ' ' + currencyArg + ' = ' + (quant*itemPrice).toFixed(2) + ' ' + currencyArg  )}).fadeIn(200);

	}

	 
	
		 
    /*
    |--------------------------------------------------------------------------
    | Allow input in form quantity field digits only, using a RegExp. Actually id does not allow to print eaither int or string
    |--------------------------------------------------------------------------
    |
    |
    */
    $('.item-quantity').keydown(function(e) { 
		
		var digits = /\D/g;		
		if (digits.test(Number(this.value))){
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, ''); alert('Ok int');
        } else {
			alert("NaN");
			e.preventDefault();
		}
    });
	

    	 
    /*
    |--------------------------------------------------------------------------
    | Return normal text, bg and attribute to "Add to cart" button if it was changed by -- for instance when you  -- last item in modal window
    |--------------------------------------------------------------------------
    |
    |
    */
    
	function normalizeAddToCartButton(thisX){
		$('.submitX').val('Add to cart');
		$('.submitX').css('background', '#717fe0');
		$('.sum').html(''); //clear field with summing (in modal), e.g 16.16 x 2 = 33.28
		var q = thisX.getAttribute("data-quantityZ");
		$('.item-quantity').val(q);  	
    }
	
});// end ready	
		
}()); //END IIFE (Immediately Invoked Function Expression)