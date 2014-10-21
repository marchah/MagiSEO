function Report() {}

var attachEventOnButtonsReportSolved = function () {
    $('.btn-report-solved').click(function() {
        var reportLine = $(this).parent().parent();
        $.ajax({
	   type: "POST",
	   url: urlServer + ajaxFolderPath + "reportAjax.php",
	   data:{nameRequest: "reportSolved", idReport: $(this).parents().children('.report-id').text()},
	   dataType: document.json,
	   error: function (xhr) { if (xhr.status == 401) alert('Error: ' + xhr.statusText); console.log('error:', xhr.responseText); },
	   success: function (flag) {
                    if (flag == true) {
                        reportLine.remove();
                        GeneralInformation.getAll();
                    }
                    else
                        alert("Error: impossible to solved this report.");     
		}
	});
        
    });
};

Report.displayButtonsReportSolved = function () {
    $('#table-all-report-thead-tr').prepend('<th>Action</th>');
    $('.table-all-report-tbody-tr').each( function(){
        $(this).prepend('<td><button class="btn btn-success btn-report-solved" type="button">Solved</button></td>');
    });
    attachEventOnButtonsReportSolved();
}

attachEventOnButtonsReportSolved();