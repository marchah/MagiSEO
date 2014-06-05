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
        }
    });
}

Server.addSlaveServer = function (ipv4, login, password) {
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
                Server.displayNewPanelServer();
            else
                alert(str);
        },
        complete : function() {
            clearInterval(x);
            Server.progressInstall();
        }
    });
}

Server.progressInstall = function () {
    $("#progress-bar-container-install-server").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateInstall"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-install-server").css("width"));
                    $("#progress-bar-install-server-slave").css("width", maxSize / max * current + "px");
                }
                $("#state-install-server-slave").text(msg);
            }
    });
}

/*
Server.updateInfo = function(form, id, ipv4, name, login, password) {
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "serverAjax.php",
       data:{nameRequest: "updateInfo", id: id, ipv4: ipv4, name: name, login: login, password: password},
       dataType: document.json,
       error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText);$(form).find(".img_loading_update_server").css('display', 'none');},
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
}*/


$('#add-slave-server').click(function() {
    $("#add-slave-server-failed").text('');
    $("#add-slave-server-failed").css('display', 'none');
    $('#AddSlaveServerModal').modal();
});

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
    $('#AddSlaveServerModal').modal('hide');
});

//attachEventOnUpdateServerButton();