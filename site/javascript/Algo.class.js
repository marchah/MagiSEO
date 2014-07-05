function Algo() {}

var attachEventOnButtonLaunchAlgoOnVM = function () {
    $('#launch-algo-vm').click(function() {
        $("#launch-algo-vm-failed").text('');
        $("#launch-algo-vm-failed").css('display', 'none');
        $('#LaunchAlgoOnVMModal').modal();
    });
};
attachEventOnButtonLaunchAlgoOnVM();

Algo.displayButtonLaunchAlgoOnVM = function () {
        $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "getHTMLCodeAjax.php",
	   data:{nameRequest: "getHTMLButtonLaunchAlgoOnVM"},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (HTMLCode) {
			if (HTMLCode != false) {
                            $('#panel-launch-algo-vm').append(HTMLCode);
                            attachEventOnButtonLaunchAlgoOnVM();
                        }
		}
	});
};


Algo.launchAlgoOnVM = function (idVM, URLSite) {
    $.ajax({
       type: "POST",
       url: urlServer + ajaxFolderPath + "algoAjax.php",
       data:{nameRequest: "launchAlgoOnVM", idVM: idVM, URLSite: URLSite},
       dataType: document.json,
       error: function (xhr) { console.log('error:', xhr.responseText);},
       success: function (str) {
            if (str != true) 
                alert(str);
        },
    });
}

function Require(errorMsg) {
    $("#launch-algo-vm-failed").text(errorMsg);
    $("#launch-algo-vm-failed").css('display', 'inline');
    $("#launch-algo-vm-failed").css('color', 'red');
}

$('#launch-algo-vm-button').click(function(){
    $("#launch-algo-vm-failed").text('');
    $("#launch-algo-vm-failed").css('display', 'none');
    if ($("#vm-available-ip").val().length < 1) {
        Require("VM required");
        $("#vm-available-ip").focus();
        return ;
    }
    if ($("#url-site").val().length < 1) {
        Require("URL Site required");
        $("#url-site").focus();
        return ;
    }
    Server.LaunchAlgoOnVM($("#vm-available-ip").val(), $("#url-site").val());
    $("#launch-algo-vm-failed").val('');
    $("#url-site").val('');
    $('#LaunchAlgoOnVMModal').modal('hide');
});
