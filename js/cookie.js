// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster

var cookieDuration = 14; // Number of days before the cookie expires, and the banner reappears
var cookieName = 'IMcomplianceCookie'; // Name of our cookie
var cookieValue = 'on'; // Value of cookie

function createCookie(name, value, days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = "; expires=" + date.toGMTString();
  } else {
	  var expires = "";
  }

  document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    begin = dc.indexOf(prefix);
    if (begin == -1) {
    	return null
    }

    var end = dc.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }

    return decodeURI(dc.substring(begin + prefix.length, end));
} 

function eraseCookie(name) {
  createCookie(name, "", -1);
}

function showCookieBanner() {
  if (getCookie(window.cookieName) != window.cookieValue) {
	  var element = document.getElementById('cookie_banner');
	  element.style.display = "block";
  }
};

function removeMe() {
  createCookie(window.cookieName, window.cookieValue, window.cookieDuration); // Create the cookie
  var element = document.getElementById('cookie_banner');
  element.parentNode.removeChild(element);
}
