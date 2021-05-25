//autocomplete both for '/shopSimple' or admin '/admin-products' routes  .....autocompletes products by "sh_device_type" & "shop_title"
//works both on user 'shopSimple' or admin 'admin-products' routes and redirects to different <a href>
//JQ autocomplete UI,(+ must include JQ_UI.js + JQ_UI.css in index.php + must pass a PHP var 'productsX' (with autocomplete DB list) to JS), for example by => var productsX = {!! $allProductsSearchBar->toJson() !!};
$(document).ready(function(){
	 
	//var productsX is passed in View with var productsX = {!! $allProductsSearchBar->toJson() !!};
	if (typeof productsX === 'undefined') { 
	    alert ('Products are not passed');
		return false;
	}
	
	//Getting current URL to construct <a href> in autocomplete. Must be redone after pretty URLS
	var path = window.location.href;                             //http://localhost/.../.../public/admin-products
	var urlX = path.substring(0, path.lastIndexOf("/") /*- 1*/); //http://localhost/.../.../public	
	
	//array which will contain all products for autocomplete
	var arrayAutocomplete = [];
	
	//Loop through passed php object, object is echoed in JSON in Controller Product/action Shop
	for (var key in productsX) {
		arrayAutocomplete.push(  { label: productsX[key]['sh_device_type'] + " " + productsX[key]['shop_title'] + "  => " +  productsX[key]['shop_price'], value: productsX[key]['shop_id'] }  ); //gets name of every user and form in this format to get and lable and value(Name & ID)

	}
	
	
    //Amendmenents in order one autocomplete can work both on user 'shopSimple' or admin 'admin-one-product' routes
	//Redirects to different < a href> when users click autocompleted product
	//we see here what is the route and determines what <a href> to use, when users click autocompleted product
	var allPath = path.split('/');
	var currenrRoute = allPath[allPath.length -1]; //returns 'admin-products' or 'shopSimple'
	if(currenrRoute == 'shopSimple'){
		var adminOrUserURL = 'showOneProduct' 
	}
	
	if(currenrRoute == 'admin-products'){
		var adminOrUserURL = 'admin-one-product'; //url to show one product
	}
	//End Amendmenents in order one autocomplete can work both on user '/shopSimple' or admin '/admin-products' routes
	
	
    //Autocomplete itself
    $( function() {	
	
	    //fix function for autocomplete (u type email in <input id="userName">, get autocomplete hints and onSelect puts email value (i.e user ID to) to hidden <input id="userID">)
	    function displaySelectedCategoryLabel(event, ui) {
            $("#searchProduct").val(ui.item.label); //ui.item.label //ui.item.value
            //event.preventDefault();
        };
		
		
		//Autocomplete, wrap hints in URL <a href>
		$("#searchProduct").autocomplete({
            minLength: 1,
            source: arrayAutocomplete, //array from where take autocomplete
		    select: function (event, ui) {
                displaySelectedCategoryLabel(event, ui);
            },
        }).data("ui-autocomplete")._renderItem = function (a, b) {
            return $("<li></li>")
            .data("item.autocomplete", b)
            .append('<a href="' + urlX + '/' + adminOrUserURL + '/' + b.value + '"> ' + b.label + '</a>' ) 
            .appendTo(a);
        };
   } );

});