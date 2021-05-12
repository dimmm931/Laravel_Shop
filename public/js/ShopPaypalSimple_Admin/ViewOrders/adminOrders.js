
//Js for Admin viewing all Orders in  Admin Panel

(function(){ //START IIFE (Immediately Invoked Function Expression)

  $(document).ready(function(){
	
	
	//Click "change button' button
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	  $(document).on("click", '.change-status-click', function(e) {   // this  click  is  used  to   react  to  newly generated cicles;
		   
		   e.preventDefault(); //prevent submit form
		   var form = $(this).parents('form'); //gets this current form //FIX
		   
		   var prevStatus = this.getAttribute("data-prev-status"); //gets current status. I.e "proceeded, not-proceeded". Set in hml by adding to button data-prev-status={{$v->ord_status}} 
		   //alert(prevStatus);
		   //console.log($(this).parent().parent().parent().html());
		   //alert($(this).parent().parent().parent().find(":selected").text());
		   
		   //var nowSelected = $(this).parent().parent().parent().find(":selected").text(); //gets now selected <option> text
		   var nowSelected = $(this).parent().parent().parent().find(":selected").val(); //gets now selected <option> value
		   
		   
		   //check if admin tries set the status that is already set as current. Just for front-end check, relevant back-end validation rule is also available
			if(nowSelected  == prevStatus){
			  swal({html:true, title:'Attention!', text:'Please note that status <b>' + nowSelected + '</b> is already set. Change it if necessary. </br>  NB:Back-end validation is also available.', type: 'error'});
			  return false;
		   } 
		   
		   
		   
		   //Swal confirm
		   swal({
			    html:true,
                title: "Are you sure to change status  <i>{" + prevStatus + "}</i> to  <i> " + nowSelected  + " </i>?",
                text: "Status will be changed!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, go ahead!',
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
           },
           function(isConfirm){
               if (isConfirm){
			       	swal({ html:true, title:'Attention!', text:'Changing <b>here</b>....</br>  ', type: 'error'});
                    //$(this).unbind('submit').submit();
					//$(".detach-role").get(0).allowDefault = true;
					//e.trigger('click');
					 //$("form:not('#press')").submit()
					 form.submit(); //$(".detach").eq(classNumber).submit();
					 return true; 
	
                
				
               } else {
                   swal("Cancelled", "You cancelled deleting.", "error");
                   //e.preventDefault();
				   return false;
               }
            });	
           
		   
		   
	  });
	
    // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
   
	 


	
  });
  // end ready	
	
	
}()); //END IIFE (Immediately Invoked Function Expression)