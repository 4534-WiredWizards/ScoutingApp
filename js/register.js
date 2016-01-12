$(document).ready(function() {
   $('#registerForm').on('submit', function() {
      // http://api.jquery.com/jquery.ajax/
      // api/register
	  var xhttp;
	  if (window.XMLHttpRequest) {
	     xhttp = new XMLHttpRequest();
	  } else {
	     // code for IE6, IE5
	     xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xhttp.onreadystatechange = function() {
	     if (xhttp.readyState == 4 && xhttp.status == 200) {
	        document.getElementById("response").innerHTML = xhttp.responseText;
	        console.log("---SERVER---: " + xhttp.responseText);
	     }
	  };
	  xhttp.open("POST", "api/pages/register.php", true);
	  var sendString = document.getElementById("registerForm").serialize();
	  xhttp.send(sendString);
   });
});
