function GeneralInformation() {}

GeneralInformation.getAll = function() {
    $.ajax({
        type: "POST",
        url: urlServer + ajaxFolderPath + "getGeneralInformationAjax.php",
        data: {nameRequest: "getAll"},
        error: function (xhr) { console.log('error:', xhr.responseText); },
        success: function (nbAll) {
            var result = nbAll.split('/');
            $('#nb_slave_server').text(result[0]);
            $('#nb_vm').text(result[1]);
            $('#nb_error').text(result[2]);
            //$('#nb_warning').text(result[2]);
            if (result[2] + result[3] > 0)
                $('#alert_pb').html('<span class="label label-danger">' + (parseInt(result[2]) + parseInt(result[3])) + '</span>');
        }
    });
}

GeneralInformation.getNbErrorAndWarning = function() {
    $.ajax({
        type: "POST",
        url: urlServer + ajaxFolderPath + "getGeneralInformationAjax.php",
        data: {nameRequest: "getNbErrorAndWarning"},
        error: function (xhr) { console.log('error:', xhr.responseText); },
        success: function (nbAll) {
            var result = nbAll.split('/');
            if (result[2] + result[3] > 0)
                $('#alert_pb').html('<span class="label label-danger">' + (parseInt(result[2]) + parseInt(result[3])) + '</span>');
        }
    });
}

GeneralInformation.getLog = function() {
    $.ajax({
        type: "POST",
        url: urlServer + ajaxFolderPath + "getGeneralInformationAjax.php",
        data: {nameRequest: "getLog"},
        error: function (xhr) { console.log('error:', xhr.responseText); },
        success: function (log) {
            var moreLog = 	'<li class="media">'
                                    +'<div class="media-object pull-left">'
                                            +'<i class="ico-loop4"></i>'
                                    +'</div>'
                                    +'<div class="media-body">'
                                            +'<a href="javascript:void(0);" class="media-heading text-primary">Load more feed</a>'
                                    +'</div>'
                                +'</li>';
            $('#list_log').html(log + moreLog);
        }
    });
}

GeneralInformation.getAll();
GeneralInformation.getLog();
