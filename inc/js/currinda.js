
function currinda_login() {
	var child = window.open(WPURLS.siteurl+'/?option=currinda_user_login'); 
}

function currinda_child() {
	window.opener.location.reload();
	window.close();
}


