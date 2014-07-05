function Authentification() {}

Authentification.authentification = function(userLogin, userPassword) {
	$.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "authentificationAjax.php",
	   data:{nameRequest: "authentification", login: userLogin, password: userPassword},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 404) authFailed("Incorrect login/password"); console.log('error:', xhr.responseText); $("#img_loading_auth").css('display', 'none');},
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
	   url: urlServer + ajaxFolderPath + "authentificationAjax.php",
	   data:{nameRequest: "disconnection"},
	   dataType: document.json,
	   error: function (xhr) { console.log('error:', xhr.responseText); location.reload(); },
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
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLUSerButtonAuth"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('#btn_authentification').replaceWith(HTMLCode);
                            attachEventOnDisconectButton();
                            Server.displayButtonsManageServer();
                            Server.displayButtonAddServer();
                            VM.displayButtonsManageVM();
                            VM.displayButtonAddVM();
                            Report.displayButtonsReportSolved();
                            Algo.displayButtonLaunchAlgoOnVM();
                        }
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
