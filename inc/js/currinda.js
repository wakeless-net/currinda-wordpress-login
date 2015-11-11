
function currinda_login() {
	var child = window.open(window.location.protocol + '//' + window.location.host + '/?option=currinda_user_login'); 
}

function currinda_child() {
	window.opener.location.reload();
	window.close();
}


