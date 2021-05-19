(function(){ //START IIFE (Immediately Invoked Function Expression)
$(document).ready(function(){
	
	  	 
    /*
    |--------------------------------------------------------------------------
    | LOADER SECTION
    |--------------------------------------------------------------------------
    |
    |
    */
    
	var showPage = function(){   
        document.getElementById("loaderX").style.display = "none"; //hides loader
        document.getElementById("all").style.display = "block";    //show div id="all"
    }
	
	
	function appendLoaderDiv(){
	    var elemDiv = document.createElement('div');
	    elemDiv.id = "loaderX";
        //elemDiv.style.cssText = 'position:absolute;width:100%;height:100%;opacity:0.3;z-index:100; top:20px;';
	    //$("#loaderX").append('<img id="theImg" src="images/load.gif" />');
	    //elemDiv.innerHTML = '<img id="theImg" src="images/load.gif" />'; 
	    //elemDiv.style.backgroundColor = "black";
	    //$("#loaderX").css("background", "url('images/load.gif')");
        document.body.appendChild(elemDiv);
	} 
	   
	if(document.getElementById("all") !== null){ //additional check to avoid errors in console in actions, other than actionShowAllBlogs(), when this id does not 
	    appendLoaderDiv(); //appends a div id="loaderX" with pure CSS loader to body, no code in index.php, just css to mycss.css
	    var myVar = setTimeout(showPage, 1000);
	}
	
		
}); // end ready		
	
}()); //END IIFE (Immediately Invoked Function Expression)