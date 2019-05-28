jQuery(document).ready(function(){
	jQuery('#currinda_login').on('submit',function(e){
		jQuery.post(jQuery(this).attr('action'),jQuery(this).serialize(),function(result){
			var obj = jQuery.parseJSON(result);
			if(obj.success == true){
				jQuery.post(WPURLS.siteurl+'/?option=currinda_ajax',{'userData':obj.userData},function(resultajax){
					var robj = jQuery.parseJSON(resultajax);
					if(robj.success == true){
						location.reload();
					}
				});
			}else{
				jQuery('#error_container').html("<p>"+obj.msg+"</p>");
			}
		});
		e.preventDefault();
		return false;
	});
});



function currinda_login() {
	var child = window.open(WPURLS.siteurl+'/?option=currinda_user_login'); 
}

function currinda_child() {
	window.opener.location.reload();
	window.close();
}


