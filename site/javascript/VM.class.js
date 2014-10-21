function VM() {}

VM.hideProgressBar = function () {
    GeneralInformation.getNbErrorAndWarning();
    $("#state-vm").text("");
    $("#progress-bar-vm").css("width", "0px");
    $("#progress-bar-container-vm").css("display", "none");
}

VM.progressInstall = function () {
    $("#progress-bar-container-vm").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateInstallVM"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-vm").css("width"));
                    $("#progress-bar-vm").css("width", maxSize / max * current + "px");
                }
                $("#state-vm").text(msg);
            }
    });
}

VM.progressDesinstall = function () {
    $("#progress-bar-container-vm").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateDesinstallVM"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-vm").css("width"));
                    $("#progress-bar-vm").css("width", maxSize / max * current + "px");
                }
                $("#state-vm").text(msg);
            }
    });
}

VM.progressUpdate = function () {
    $("#progress-bar-container-vm").css("display", "block");
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "progressAjax.php",
       data:{nameRequest: "getStateUpdateVM"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (step) {
                var result = step.split('/');
                
                var current = parseInt(result[0]) + 1;
                var msg = result[1];
                var max = parseInt(result[2]) + 1;
                
                if (current >= 0) { 
                    var maxSize = parseInt($("#progress-bar-container-vm").css("width"));
                    $("#progress-bar-vm").css("width", maxSize / max * current + "px");
                }
                $("#state-vm").text(msg);
            }
    });
}

VM.displayNewPanelVM = function () {
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
       data:{nameRequest: "getHTMLPanelNewVM"},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (panel) {
            $("#list-panel").append(panel);
            attachEventOnButtonsManageVM();
        }
    });
}

VM.addVM = function (idServer, name, RAM, HDD, IpAlgo, URLClient, isArchive) {
    if ($("#progress-bar-container-vm").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(VM.progressInstall, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "VMAjax.php",
       data:{nameRequest: "installVM", idServer: idServer, name: name, RAM: RAM, HDD: HDD, IpAlgo: IpAlgo, URLClient : URLClient, isArchive : isArchive},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
            if (str == true) 
                setTimeout(VM.displayNewPanelVM, 5000); // très moche PK????
            else
                alert(str);
        },
        complete : function() {
            clearInterval(x);
            VM.progressInstall();
            setTimeout(VM.hideProgressBar, 6000);
        }
    });
}

VM.deleteVM = function (VMPanel, idVM) {
    if ($("#progress-bar-container-vm").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(VM.progressDesinstall, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "VMAjax.php",
       data:{nameRequest: "desinstallVM", idVM: idVM},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
                if (str == 1)
                    VMPanel.remove();
                else
                    alert(str);
        },
        complete : function() {
            clearInterval(x);
            VM.progressDesinstall();
            setTimeout(VM.hideProgressBar, 6000);
        }
    });
}

VM.cancelVM = function (VMPanel, ipServer, ipVM) {
    if ($("#progress-bar-container-vm").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(VM.progressDesinstall, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "VMAjax.php",
       data:{nameRequest: "cancelVM", ipServer: ipServer, ipVM: ipVM},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
                if (str == 1)
                    VMPanel.remove();
                else
                    alert(str);
        },
        complete : function() {
            clearInterval(x);
            VM.progressDesinstall();
            setTimeout(VM.hideProgressBar, 6000);
        }
    });
}

VM.updateVM = function (ipServer, idVM) {
    if ($("#progress-bar-container-vm").css("display") != "none") {
        alert("... Please Wait The End Of The Current Process ...");
        return ;
    }
    var x = setInterval(VM.progressUpdate, 2000);
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "VMAjax.php",
       data:{nameRequest: "updateVM", ipServer: ipServer, idVM: idVM},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
                if (str != 1)
                    alert(str);
        },
        complete : function() {
            clearInterval(x);
            VM.progressUpdate();
            setTimeout(VM.hideProgressBar, 6000);
        }
    });
}

var attachEventOnButtonsManageVM = function() {
    $('.remove-vm').click(function(){
        var VMPanel = $(this).parents(".server-panel");
        VM.deleteVM(VMPanel, VMPanel.find(".vm-id").text()); 
    });
    $('.cancel-vm').click(function(){
        var VMPanel = $(this).parents(".server-panel");
        VM.cancelVM(VMPanel, (VMPanel.find(".panel-title").text()).split(': ')[1], VMPanel.find(".vm-id").text()); 
    });
    $('.update-vm').click(function(){
        var VMPanel = $(this).parents(".server-panel");
        VM.updateVM((VMPanel.find(".panel-title").text()).split(': ')[1], VMPanel.find(".vm-id").text()); 
    });
};
attachEventOnButtonsManageVM();

var attachEventOnButtonAddVM = function () {
    $('#add-vm').click(function() {
        if ($("#progress-bar-container-vm").css("display") != "none") {
            alert("... Please Wait The End Of The Current Process ...");
            return ;
        }
        $("#add-vm-failed").text('');
        $("#add-vm-failed").css('display', 'none');
        $('#AddVMModal').modal();
    });
};
attachEventOnButtonAddVM();

VM.displayButtonsManageVM = function () {
    $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLButtonsManageVM"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('.panel-toolbar-vm').append(HTMLCode);
                            attachEventOnButtonsManageVM();
                        }
		}
	});
};

VM.displayButtonAddVM = function () {
        $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLButtonAddVM"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('#panel-add-vm').append(HTMLCode);
                            attachEventOnButtonAddVM();
                        }
		}
	});
};

function Require(errorMsg, elementFocus) {
    $("#add-vm-failed").text(errorMsg);
    $("#add-vm-failed").css('display', 'inline');
    $("#add-vm-failed").css('color', 'red');
    $(elementFocus).focus();
}

$('#add-vm-button').click(function(){
    $("#add-vm-failed").text('');
    $("#add-vm-failed").css('display', 'none');

    if ($("#add-vm-ip-server").val() === null) {
        Require("IPV4 Server required", "#add-vm-ip-server");
        return ;
    }
    if ($("#add-vm-name").val().length < 1) {
        Require("Name VM required", "#add-vm-name");
        return ;
    }
    if ($("#add-vm-ram").val().length < 1) {
        Require("RAM VM required", "#add-vm-ram");
        return ;
    }
    if (!Tools.isInt($("#add-vm-ram").val())) {
        Require("RAM VM have to be an integer", "#add-vm-ram");
        return ;
    }
    if (parseInt($("#add-vm-ram").val()) < minSizeRAM) {
        Require("RAM VM have to be >= " + minSizeRAM, "#add-vm-ram");
        return ;
    }
    if ($("#add-vm-hdd").val().length < 1) {
        Require("HDD VM required", "#add-vm-hdd");
        return ;
    }
    if (!Tools.isInt($("#add-vm-hdd").val())) {
        Require("HDD VM have to be an integer", "#add-vm-hdd");
        return ;
    }
    if (parseInt($("#add-vm-hdd").val()) < minSizeHDD) {
        Require("HDD VM have to be >= " + minSizeHDD, "#add-vm-hdd");
        return ;
    }
    if ($("#add-vm-ip-algo").val().length < 1) {
        Require("Name IP Algo Server required", "#add-vm-ip-algo");
        return ;
    }
    if ($("#add-vm-url-client-site").val().length < 1) {
        Require("Name URL Client Website required", "#add-vm-url-client-sit");
        return ;
    }
    VM.addVM($("#add-vm-ip-server").val(), $("#add-vm-name").val(), $("#add-vm-ram").val(), $("#add-vm-hdd").val(), $("#add-vm-ip-algo").val(), $("#add-vm-url-client-site").val(), document.getElementById('add-vm-is-archive').checked);
    $("#add-vm-ip-server").val('');
    $("#add-vm-name").val('');
    $("#add-vm-ram").val('');
    $("#add-vm-hdd").val('');
    $("#add-vm-ip-algo").val('');
    $("#add-vm-url-client-site").val('');
    document.getElementById('add-vm-is-archive').checked = false;
    $('#AddVMModal').modal('hide');
});