    // Get the modal
	var modal = document.getElementById("smartModal");
	var hotBall = document.getElementsByClassName("hot-ball-container")[0];

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	//var specialOfferBtn = document.getElementById("specialOfferBtn")[0];
	// When the user clicks on <span> (x), close the modal
	if (span) {
		span.onclick = function() {
	  		modal.style.display = "none";
		}
	}

	if (hotBall) {
		hotBall.onclick = function() {
	  		modal.style.display = "block";
		}
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == modal) {
	    modal.style.display = "none";
	  }
	}

  	function setCookie(c_name, value,time) {
    var exdate = new Date();
    var c_value=escape(value) + "; expires=" + time  + "; path=/";
    document.cookie = c_name + "=" + c_value;
	}

  	function getCookie(c_name) {
    var i, x, y, SmartCookie = document.cookie.split(";");
    for (i = 0; i < SmartCookie.length; i++) {
        x = SmartCookie[i].substr(0, SmartCookie[i].indexOf("="));
        y = SmartCookie[i].substr(SmartCookie[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        	}
    	}
	}
	function delete_cookie(name) {
  		document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	}
	//Total time user on site
	if(!getCookie('time_spent_on_site')){
		window.addEventListener("load", function() {
	    var today = new Date();
		var time = today.getTime();
	    setCookie('time_spent_on_site',time,(aiCouponMaxtime * 60 ));
		});
	}
	//END Total time user on site

	// OPEN MODAL
	if(getCookie('time_spent_on_site')){
		window.addEventListener("scroll", function() {
			var now = new Date();
			var timeNow = now.getTime();
	    	var timeFirst = getCookie('time_spent_on_site');
	    	//var popupCheck = getCookie('smart_popup_check');
	    	var totalTime = (timeNow - timeFirst) / 1000;

	    	if( getCookie('smart_popup_check') || getCookie('smart_popup_check') == 1 ){
	    		//modal.style.display = "block";
	    		hotBall.style.display = "block";
	    	}
	    	if(totalTime >= (aiCouponMaxtime * 60 )){
	    		delete_cookie('smart_coupon_ai_time_cookie');
	    		delete_cookie('time_spent_on_site');
	    		delete_cookie('smart_popup_check');
	    		delete_cookie('smart_coupon_ai_chosen_product_id');
	    	}
		});
	}
	// END OPEN MODAL
	if( !(getCookie('smart_popup_check') || getCookie('smart_popup_check') == 1) ){
	  	window.addEventListener("beforeunload", function(event) {
	  		var today = new Date();
			var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
			var current_cookie = getCookie('smart_coupon_ai_time_cookie');
			setCookie('smart_coupon_ai_time_cookie', current_cookie + "|" + time, 86400);
	    });
  	}