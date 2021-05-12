//Js for  W3school Full Page Tabs (uses css + js file + js <button onclick="openPage()") https://www.w3schools.com/howto/howto_js_full_page_tabs.asp  */
  
//(function(){ //START IIFE (Immediately Invoked Function Expression) //Comment it otherwise => Uncaught ReferenceError: openPage is not defined



//$(document).ready(function(){  //Comment it (ready(function)), otherwise => Uncaught ReferenceError: openPage is not defined
	
    function openPage(pageName,elmnt, color) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
           tablinks[i].style.backgroundColor = "";
        }
        document.getElementById(pageName).style.display = "block";
        elmnt.style.backgroundColor = color;
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
	 


	
//});
// end ready	
	
	
//}()); //END IIFE (Immediately Invoked Function Expression)

  