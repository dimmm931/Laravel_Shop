

//Js for Cart (SHOP {Simple})
  
(function(){ //START IIFE (Immediately Invoked Function Expression)


$(document).ready(function(){
	
	
	
	 //==================================================================
     //[ +/- num product ]*/
	
	//Plus ++
    $('.btnCart-plus').on('click', function(){ 
	
		var numProduct1 = Number($(this).parent().find('input:eq(1)').val()); //gets the input with quantity
		
		//filter input, clears any chars that are not inegers or float (in case user print it manually)
		var numProduct = filterNum(numProduct1.toString());
		
		var price = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets price from {data-currX
		//alert(numProduct + " " + price);
		
		$(this).parent().find('input:eq(1)').val(numProduct + 1); //html new quantitity value++
		
		
        $(this).parent().next().html(((numProduct + 1) * price).toFixed(2) + " " + currency);//html Div .one-pr-sum with new sum (this price * this quant)). Use {toFixed(2)} to return 33.00 not 33.0008	
		
		calcAllSum(); //recalculate the whole sum after ++/-- on any item in cart
		
		
	    /*
	    //in case product was in cart, user minus-- it, till the last, and button was changed to "Remove from cart?"
		if($('.submitX').val() != 'Add to cart'){
			$('.submitX').val('Add to cart');
			$('.submitX').css('background', '#717fe0');
		}
		
		var numProduct = Number($(this).closest('div').next().find('input:eq(1)').val()); //gets current input quantity. Use {input:eq(1)}, as {input:eq(0)} is a CSRF token
		
		$(this).closest('div').next().find('input:eq(1)').val(Number(numProduct) + 1); //html new value++
		
		
		//var price = $(this).parent().parent().siblings().find('.price-x').html(); //gets price-x. Working, but reassigned to {data-priceX}
		var price = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets DB currency from {data-currX} 
		console.log("price " + price);
		calcPrice(numProduct+1, price, currency);
		
		
		
		*/
    });
	
	 

	 
	  
	 
	//*==================================================================
	//Minus --
    $('.btnCart-minus').on('click', function(){
		
			
		var numProduct1 = Number($(this).parent().find('input:eq(1)').val()); //gets the input with quantity
		
		//filter input, clears any chars that are not inegers or float (in case user print it manually)
		var numProduct = filterNum(numProduct1.toString());
		
		if(numProduct == 0){
			swal("Stop!", "Can't go further. This product is already nulled", "warning");
			return false;
		}
		
		
		var price = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets price from {data-currX}
		
		//alert(numProduct + " " + price);
		$(this).parent().find('input:eq(1)').val(numProduct - 1); //html new quantitity value++
		
        $(this).parent().next().html(((numProduct - 1) * price).toFixed(2) + " " + currency);//html Div .one-pr-sum with new sum (this price * this quant)). Use {toFixed(2)} to return 33.00 not 33.0008	
		
		calcAllSum(); //recalculate the whole sum after ++/-- on any item in cart
		
		
		
		
		
		
		/*
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
		
		//var price = $(this).parent().parent().siblings().find('.price-x').html(); //gets the price from modal, price-x. Working, but reassigned to {data-priceX}
		var price = this.getAttribute("data-priceX"); //gets price from {data-priceX}
		var currency = this.getAttribute("data-currX"); //gets DB currency from {data-currX}
		
		
		calcPrice((numProduct-1), price, currency); //quantity*price
		*/
    });
	
	
	
	
	
	
	
	//*==================================================================
	//recalculate the whole sum after ++/-- on any item in cart
	function calcAllSum(){
		 //setTimeout(function(){  
		 
		//set classes to var 
		var priceSpan = $(".priceX");
		var quantSpan = $(".item-quantity");
		
		var sum = 0;
		
		for(var i = 0; i < priceSpan.length; i++){
			//alert( priceSpan[i].innerHTML);
			//alert(   $(quantSpan[i]).val());	
			var s = priceSpan[i].innerHTML * $(quantSpan[i]).val();
			sum+= s;
		}
		 sum = sum.toFixed(2);
		$("#finalSum").stop().fadeOut(100, function(){ $(this).html(sum + "₴")}).fadeIn(100); //with animation
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 //NOT USED HERE
	 //==================================================================
	 //calcs & html the amount of sum for all items, i,e 2x16.66 = N
	 function calcPrice(quant, itemPrice, currencyArg){
		//$('.sum').html(Number(quant) + ' item x ' + itemPrice + '₴ = ' + (quant*itemPrice).toFixed(2) + '₴' );
		  $('.sum').stop().hide(100,function(){ $(this).html( Number(quant) + ' item x ' + itemPrice + ' ' + currencyArg + ' = ' + (quant*itemPrice).toFixed(2) + ' ' + currencyArg  )}).fadeIn(200);

	 }

	 
	
	//*==================================================================
	//on change the input, do recalculating
	//$('.item-quantity').on('change', function() {alert( "Handler." );});
	//$('.item-quantity').on('input', function(){ alert('changed input ') }); 
	//DOES NOT WORK!!!!!!!!
	$("body").on('DOMSubtreeModified', ".item-quantity", function() {
        alert('changed');
    });
	
	





	
	
	// Allow input in form quantity field digits only, using a RegExp. Actually id does not allow to print eaither int or string
	  $('.item-quantity').keydown(function(e) { alert('No manual input');
        //this.value = this.value.replace(/[^0-9]/g, '');
		
		var digits = /\D/g;		
		 if (digits.test(Number(this.value))){
              // Filter non-digits from input value.
              this.value = this.value.replace(/\D/g, ''); alert('Ok int');
          } else {
			  alert("NaN");
			  e.preventDefault();
		  }
    });
	

  
	//allow only digits and float 
    const filterNum = (str) => {
       const numericalChar = new Set([ ".",",","0","1","2","3","4","5","6","7","8","9" ]);
       str = str.split("").filter(char => numericalChar.has(char)).join("");
	   str = Number(str);
       return str;
    }
	 


	
});
// end ready	
	
	
}()); //END IIFE (Immediately Invoked Function Expression)