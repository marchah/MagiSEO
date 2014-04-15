function GeneralInformation() {}

GeneralInformation.getNbSlaveServer = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getNbSlaveServer"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
	success: function (nbSlaveServer) {
			alert('Slave Server: ' + nbSlaveServer);
		}
	});
}

GeneralInformation.getNbError = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getNbError"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
	success: function (nbError) {
			alert('Error: ' + nbError);
		}
	});
}

GeneralInformation.getNbWarning = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getNbWarning"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
	success: function (nbWarning) {
			alert('Warning: ' + nbWarning);
		}
	});
}

GeneralInformation.getNbBug = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getNbBug"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
	success: function (nbBug) {
			alert('Bug: ' + nbBug);
		}
	});
}

GeneralInformation.getAll = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getAll"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
	success: function (nbAll) {
			var result = nbAll.split('/');
			$('#nb_slave_server').text(result[0]);
			$('#nb_error').text(result[1]);
			$('#nb_warning').text(result[2]);
			//$('nb_bug').html(result[3]);
			if (result[1] + result[2] /*+ result[3]*/ > 0)
				$('#alert_pb').html('<span class="label label-danger">' + (parseInt(result[1]) + parseInt(result[2]) /*+ parseInt(result[3])*/) + '</span>');
		}
	});
}

GeneralInformation.getLog = function() {
	$.ajax({
	type: "POST",
	url: urlServer + "ajax/getGeneralInformationAjax.php",
	data: {nameRequest: "getLog"},
	error: function (xhr, ajaxOptions, thrownError) { console.log('error:', xhr); },
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