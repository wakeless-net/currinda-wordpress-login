
function auth() {
	if(jQuery('#currinda_iframe').length) {
		var urlpath =  new URL(jQuery('#currinda_iframe').attr('src')).pathname;
		var iframe = jQuery('#currinda_iframe');
		if(urlpath == '/logout') {
			iframe.attr('src', iframe.data('authurl'));
		} else {
			iframe.css('display', 'block');
		}
	}	
}

function currinda_login() {
	var child = window.open(WPURLS.siteurl+'/?option=currinda_user_login'); 
}

function currinda_reload_page() {
	window.parent.location.reload();
}


