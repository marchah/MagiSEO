function Server() {}

Server.updateInfo = function(form, id, ipv4, name, username, password) {
	$.ajax({
	   type: "POST",
	   url: urlServer + "ajax/serverAjax.php",
	   data:{nameRequest: "updateInfo", id: id, ipv4: ipv4, name: name, username: username, password: password},
	   dataType: document.json,
	   error: function (xhr, ajaxOptions, thrownError) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr);$(form).find(".img_loading_update_server").css('display', 'none');},
	   success: function (str) {
			$(form).find(".img_loading_update_server").css('display', 'none');
			var nb = parseInt(str);
			if (nb == 1)
				alert("Upload Success");
			else if (nb == 0)
				alert("Any Changed");
			else
				alert("Error: more than 1 row updated");
		}
	});
}

var attachEventOnUpdateServerButton = function() {
	$('.btn-update-server').click(function(){
		var form = ($(this).parent().parent());
		$(form).find(".img_loading_update_server").css('display', 'inline');
		var id = $(form).find('.id-server-slave').text();
		var ipv4 = $(form).find('.ipv4-server-slave').val();
		var name = $(form).find('.name-server-slave').val();
		var username = $(form).find('.username-server-slave').val();
		var password = $(form).find('.password-server-slave').val();
		Server.updateInfo(form, id, ipv4, name, username, password);
	});
}

attachEventOnUpdateServerButton();