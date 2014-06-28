function Server() {}

Server.displayNewPanelServer = function () {
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
       data:{nameRequest: "getHTMLPanelNewServer"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (panel) {
            $("#list-planel").append(panel);
            attachEventOnButtonsManageServer();
        }
    });
}

Server.hideProgressBar = function () {
    $("#state-server-slave").text("");
    $("#progress-bar-server-slave").css("width", "0px");
    $("#progress-bar-container-server").css("display", "none");
}

Server.addSlaveServer = function (ipv4, login, password) {
    if ($("#progress-bar-container-server").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(Server.progressInstall, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "serverAjax.php",
       data:{nameRequest: "installServer", ipServerSSH: ipv4, login: login, password: password},
       dataType: document.json,
       /*beforeSend: function () {
           Server.progressInstall();
       },*/
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
            if (str == true) 
                setTimeout(Server.displayNewPanelServer, 5000); // très moche PK????
            else
                alert(str);
        },
        complete : function() {
            clearInterval(x);
            Server.progressInstall();
            setTimeout(Server.hideProgressBar, 6000);
        }
    });
}

Server.progressInstall = function () {
    $("#progress-bar-container-server").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateInstallServer"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-server").css("width"));
                    $("#progress-bar-server-slave").css("width", maxSize / max * current + "px");
                }
                $("#state-server-slave").text(msg);
            }
    });
}

Server.progressDesinstall = function () {
    $("#progress-bar-container-server").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateDesinstallServer"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                //alert(step);
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-server").css("width"));
                    $("#progress-bar-server-slave").css("width", maxSize / max * current + "px");
                }
                $("#state-server-slave").text(msg);
            }
    });
}

Server.deleteSlaveServer = function (serverPanel, ipServerSSH, login) {
    if ($("#progress-bar-container-server").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(Server.progressDesinstall, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "serverAjax.php",
       data:{nameRequest: "desinstallServer", ipServerSSH: ipServerSSH, login: login},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
                if (str == 1)
                    serverPanel.remove();
                else
                    alert(str);
        },
        complete : function() {
            clearInterval(x);
            Server.progressDesinstall();
            setTimeout(Server.hideProgressBar, 6000);
        }
    });
}

/*$(".remove-server").click(function () {
    var serverPanel = $(this).parents(".server-panel");
    Server.deleteSlaveServer(serverPanel, serverPanel.find(".server-ip").text(), serverPanel.find(".server-username").text());    
});*/

var attachEventOnButtonsManageServer = function() {
    $('.remove-server').click(function(){
        var serverPanel = $(this).parents(".server-panel");
        Server.deleteSlaveServer(serverPanel, serverPanel.find(".server-ip").text(), serverPanel.find(".server-username").text()); 
    });
};

attachEventOnButtonsManageServer();

Server.displayButtonsManageServer = function () {
    $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLButtonsManageServer"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('.panel-toolbar-server').append(HTMLCode);
                            attachEventOnButtonsManageServer();
                        }
		}
	});
};

var attachEventOnButtonAddServer = function () {
    $('#add-slave-server').click(function() {
        if ($("#progress-bar-container-server").css("display") != "none") {
            alert("... Please Wait The End Of The Current Process ...");
            return ;
        }
        $("#add-slave-server-failed").text('');
        $("#add-slave-server-failed").css('display', 'none');
        $('#AddSlaveServerModal').modal();
    });
};

attachEventOnButtonAddServer();

Server.displayButtonAddServer = function () {
        $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLButtonAddServer"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('#panel-add-server-slave').append(HTMLCode);
                            attachEventOnButtonAddServer();
                        }
		}
	});
};

function Require(errorMsg) {
    $("#add-slave-server-failed").text(errorMsg);
    $("#add-slave-server-failed").css('display', 'inline');
    $("#add-slave-server-failed").css('color', 'red');
}

$('#add-slave-server-button').click(function(){
    $("#add-slave-server-failed").text('');
    $("#add-slave-server-failed").css('display', 'none');
    if ($("#add-slave-server-ip").val().length < 1) {
        Require("IPV4 required");
        $("#add-slave-server-ip").focus();
        return ;
    }
    if ($("#add-slave-server-login").val().length < 1) {
        Require("Login required");
        $("#add-slave-server-Login").focus();
        return ;
    }
    if ($("#add-slave-server-password").val().length < 1) {
        Require("Password required");
        $("#add-slave-server-password").focus();
        return ;
    }
    
    if ($("#add-slave-server-password").val() != $("#add-slave-server-password-confirmation").val()) {
        Require("Password confirmation doesn't match");
        $("#add-slave-server-password-confirmation").focus();
        return ;
    }
    Server.addSlaveServer($("#add-slave-server-ip").val(), $("#add-slave-server-login").val(), $("#add-slave-server-password").val());
    $("#add-slave-server-ip").val('');
    $("#add-slave-server-login").val('');
    $("#add-slave-server-password").val('');
    $("#add-slave-server-password-confirmation").val('');
    $('#AddSlaveServerModal').modal('hide');
});

//attachEventOnUpdateServerButton();