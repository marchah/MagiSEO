function Authentification() {}

Authentification.authentification = function(userLogin, userPassword) {
	$.ajax({
	   type: "POST",
	   url: urlServer + "ajax/authentificationAjax.php",
	   data:{nameRequest: "authentification", login: userLogin, password: userPassword},
	   dataType: document.json,
	   error: function (xhr, ajaxOptions, thrownError) { if (xhr.status == 404) authFailed("Incorrect login/password"); else console.log('error:', xhr); $("#img_loading_auth").css('display', 'none');},
	   success: function (isAuth) {
			if (isAuth == true)
				authSucces();
			$("#img_loading_auth").css('display', 'none');
		}
	});
}

Authentification.disconnection = function() {
	$.ajax({
	   type: "POST",
	   url: urlServer + "ajax/authentificationAjax.php",
	   data:{nameRequest: "disconnection"},
	   dataType: document.json,
	   error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); location.reload(); },
	   success: function () {
			location.reload() ; 
		}
	});
}

function authFailed(errorMsg) {
	$("#auth_failed").text(errorMsg);
	$("#auth_failed").css('display', 'inline');
	$("#auth_failed").css('color', 'red');
}

function authSucces() {
	$("#auth_login").val('');
	$("#auth_password").val('');
	$("#auth_failed").text('');
	$("#auth_failed").css('display', 'none');
	$('#authentificationModal').modal('hide');
	$.ajax({
	   type: "POST",
	   url: urlServer + "ajax/getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLUSerButtonAuth"},
	   dataType: document.json,
	   error: function (xhr, ajaxOptions, thrownError) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr); },
	   success: function (HTMLCode) {
			if (HTMLCode != false)
				$('#btn_authentification').replaceWith(HTMLCode);
				attachEventOnDisconectButton();
				$('.footer-server-info').replaceWith('<button type="button" class="btn btn-success btn-update-server">Update</button><img class="img_loading_update_server" src="image/spinner.gif" alt="Loading ..." style="display:none;margin-right: 10px; float:right;margin-top: 8px;" />');
				attachEventOnUpdateServerButton();
		}
	});
}

$('#btn_authentification').click(function(){
	$('#authentificationModal').modal({});
});

$('#auth_button').click(function(){
	if ($("#auth_password").val().length < 1) {
		$("#auth_password").focus();
		return ;
	}
	$('#img_loading_auth').css('display', 'inline');
	Authentification.authentification($('#auth_login').val(), $('#auth_password').val());
	$('#auth_password').val('');
});

var attachEventOnDisconectButton = function() {
	$('#disconnection_button').click(function(){
		Authentification.disconnection();
	});
}

attachEventOnDisconectButton();
