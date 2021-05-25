/*
 |--------------------------------------------------------------------------
 | Js for ajax counting 
 |--------------------------------------------------------------------------
 |
 |
 */
 
 (function(){ //START IIFE (Immediately Invoked Function Expression)

$(document).ready(function(){
   
    var loc = window.location.pathname;
    var dir = loc.substring(0, loc.lastIndexOf('/'));  ///laravel+Yii2_widgets/blog_Laravel/public    //yii2_REST_and_Rbac_2019/yii-basic-app-2.0.15/basic/web/manual-auto-quiz
    var urlX = dir + '/count-orders';

    //run the function with delay
    setTimeout(function(){ countUserRegisterRequests(); }, 3000);
    myVar = setInterval(countUserRegisterRequests, 1000*60*10 ); //repeat every 10 min
	
	
    /*
    |--------------------------------------------------------------------------
    | Send ajax request to admin/admin-x/count-register-requests to count requests and displays to badge
    |--------------------------------------------------------------------------
    |
    |
    */
	function countUserRegisterRequests()
    { 
		// send  data  to  PHP handler  ************ 
        $.ajax({
            url:  urlX,
            type: 'GET',
			dataType: 'JSON', 
            success: function(data) { 
				displayBadgeValue(data);
            },  //end success
			error: function (error) {
				console.log(error);
				alert('fail counting');
				//$(".all-6-month").stop().fadeOut("slow",function(){ $(this).html("Failed")}).fadeIn(2000);
            }	
        });
                                       
	}
	
	
	
	
   /*
    |--------------------------------------------------------------------------
    | Html the count badge
    |--------------------------------------------------------------------------
    |
    |
    */
	function displayBadgeValue(data)
	{
		if(data  >= 0 ){ 
			if(!$('.bb:eq(2)').hasClass('badge1')) {
				$('.bb:eq(2)').addClass('badge1');
			}
            //html quantity for Orders category
			$('.bb:eq(1)').attr('data-badge', data); //$('.badge1:eq(0)').stop().fadeOut("slow",function(){ $(this).attr('data-badge', data.count) }).fadeIn(2000);   
		} else {
		    $('.bb:eq(2)').removeClass('badge1'); 
		}
	}

});
// end ready			
}()); 