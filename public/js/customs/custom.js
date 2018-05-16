/*****Deshabilita la tecla enter*****/
jQuery(document).ready(function() {
	toastr.options = {
		"closeButton": true
	}

	jQuery.fn.datepicker.dates['es'] = {
		days: ['Domingo', 'Lunes', 'Martes', 'Mi\u00E9rcoles', 'Jueves', 'Viernes', 'S\u00E1bado'], 
		daysShort: ['Dom', 'Lun', 'Mar', 'Mi\u00E9', 'Jue', 'Vie', 'S\u00E1b'], 
		daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S\u00E1'], 
		months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'], 
		monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
	};

	jQuery('.datepicker').datepicker({
		weekStart: 1, 
		format: 'dd-mm-yyyy', 
		autoclose: true, 
		language: 'es'
	});
	/*$('form[name=agregar]').keypress(function(e) {
		if (e.which == 13) {
			return false;
		}
	});
	/*************************************************************************************************************/
	/*$('form[name=modificar]').keypress(function(e) {
		if (e.which == 13) {
			return false;
		}
	});
	/*************************************************************************************************************/
	//$('#ToolTables_DataTables_Table_0_2').attr('target', '_blank');
	/*************************************************************************************************************/
	/*$('input[name=rut]').Rut({
		format_on: 'keyup'
	});
	/*************************************************************************************************************/
	/*$("#casino_empresa").change(function () {
		$("#casino_empresa option:selected").each(function () {
			casino = $(this).val();
			$.post(base_url + '/casinos/empresas_casinos_servicios/serviciosByCasino', {casino: casino}, function(data) {
				$("#servicio_empresa").html(data);
			});
		});
	});

	$("#casino-servicio").change(function () {
		$("#casino-servicio option:selected").each(function () {
			casino = $(this).val();
			$.post(base_url + '/casinos/empresas_casinos_servicios/serviciosByCasino', {casino: casino}, function(data) {
				$("#servicio-empresa").html(data);
			});
		});
	});

	$("#region").change(function () {
		$("#region option:selected").each(function () {
			region = $(this).val();
			$.post(base_url + '/mantenedores/provincias/provinciasByRegion', {region: region}, function(data) {
				$("#provincia").html(data);
			});
		});
	});

	$("#region_empresa").change(function () {
		$("#region_empresa option:selected").each(function () {
			region = $(this).val();
			$.post(base_url + '/mantenedores/provincias/provinciasByRegion', {region: region}, function(data) {
				data = data + '';
				var datos = data.split(',');
				$("#provincia_empresa").html(datos[0]);
				$("#comuna").html(datos[1]);
			});
		});
	});

	$("#provincia_empresa").change(function () {
		$("#provincia_empresa option:selected").each(function () {
			provincia = $(this).val();
			$.post(base_url + '/mantenedores/comunas/comunasByProvincia', {provincia: provincia}, function(data) {
				$("#comuna").html(data);
			});
		});
	});
	/*************************************************************************************************************/
	/*$('#check-all-casinos').change(function () {
		if ($(this).is(':checked')) {
			$("#check-casinos input[type=checkbox]").prop('checked', true);
		} else {
			$("#check-casinos input[type=checkbox]").prop('checked', false);
		}
	});

	$('.check-one-casino').change(function () {
		var cantidad_total = $(this).attr('number');
		var total = $(".check-one-casino:checked").length;
		if (!($(this).is(':checked'))) {
			if ($('#check-all-casinos').prop('checked')) {
				$("#check-all-casinos").prop('checked', false);
			}
		} else {
			if (cantidad_total == total && !($('#check-all-casinos').prop('checked'))) {
				$("#check-all-casinos").prop('checked', true);
			}
		}
	});

	$('#check-all-empresas').change(function () {
		if ($(this).is(':checked')) {
			$("#check-empresas input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
		} else {
			$("#check-empresas input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
		}
	});

	$('.check-one-empresa').change(function () {
		var cantidad_total = $(this).attr('number');
		var total = $(".check-one-empresa:checked").length;
		if (!($(this).is(':checked'))) {
			if ($('#check-all-empresas').prop('checked')) {
				$("#check-all-empresas").prop('checked', false);
			}
		} else {
			if (cantidad_total == total && !($('#check-all-empresas').prop('checked'))) {
				$("#check-all-empresas").prop('checked', true);
			}
		}
	});

	$('#check-all-servicios').change(function () {
		if ($(this).is(':checked')) {
			$("#check-servicios input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
		} else {
			$("#check-servicios input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
		}
	});

	$('.check-one-servicio').change(function () {
		var cantidad_total = $(this).attr('number');
		var total = $(".check-one-servicio:checked").length;
		if (!($(this).is(':checked'))) {
			if( $('#check-all-servicios').prop('checked') ) {
				$("#check-all-servicios").prop('checked', false);
			}
		} else {
			if (cantidad_total == total && !($('#check-all-servicios').prop('checked'))) {
				$("#check-all-servicios").prop('checked', true);
			}
		}
	});

	$('#check-all-contratos').change(function () {
		if ($(this).is(':checked')) {
			$("#check-contratos input[type=checkbox]").prop('checked', true);
		} else {
			$("#check-contratos input[type=checkbox]").prop('checked', false);
		}
	});

	$('.check-one-contrato').change(function () {
		var cantidad_total = $(this).attr('number');
		var total = $(".check-one-contrato:checked").length;
		if (!($(this).is(':checked'))) {
			if( $('#check-all-contratos').prop('checked') ) {
				$("#check-all-contratos").prop('checked', false);
			}
		} else {
			if (cantidad_total == total && !($('#check-all-contratos').prop('checked'))) {
				$("#check-all-contratos").prop('checked', true);
			}
		}
	});
	/**************************************************AGENDA*****************************************************/
	/*$("#agenda-empresa").change(function () {
		$("#agenda-empresa option:selected").each(function () {
			empresa = $(this).val();
			$.post(base_url + '/casinos/agendas/changeCompany', {empresa: empresa}, function(data) {
				data = data + '';
				var datos = data.split(',');
				$("#agenda-casino").html(datos[0]);
				$("#agenda-servicio").html(datos[1]);
				$("#agenda-inicio").html(datos[2]);
				$("#agenda-termino").html(datos[2]);
			});
		});
	});

	$("#agenda-casino").change(function () {
		$("#agenda-casino option:selected").each(function () {
			empresa = $('#agenda-empresa').val();
			casino = $(this).val();
			$.post(base_url + '/casinos/agendas/changeCasino', {empresa: empresa, casino: casino}, function(data) {
				data = data + '';
				var datos = data.split(',');
				$("#agenda-servicio").html(datos[0]);
				$("#agenda-inicio").html(datos[1]);
				$("#agenda-termino").html(datos[2]);
			});
		});
	});

	$("#agenda-servicio").change(function () {
		$("#agenda-servicio option:selected").each(function () {
			empresa = $('#agenda-empresa').val();
			casino = $('#agenda-casino').val();
			servicio = $(this).val();
			$.post(base_url + '/casinos/agendas/changeServicio', {empresa: empresa, casino: casino, servicio: servicio}, function(data) {
				data = data + '';
				var datos = data.split(',');
				$("#agenda-inicio").html(datos[0]);
				$("#agenda-termino").html(datos[0]);
			});
		});
	});
	/*************************************************************************************************************/
});
/*****************************************************************************************************************/
/*jQuery(document).ready(function() {
	var table = jQuery('.table').DataTable({
		fixedHeader: {
			header: true, 
			footer: true
		}, 
		"iDisplayLength": 10, 
		"oLanguage": {
			"sLengthMenu": 'Mostrar <select>' + 
			'<option value="10">10</option>' + 
			'<option value="25">25</option>' + 
			'<option value="50">50</option>' + 
			'<option value="100">100</option>' + 
			'<option value="-1">Todos</option>' + 
			'</select> registros', 
			'sSearch': 'Buscar: ', 
			'sInfo': 'Mostrando _START_ a _END_ de _TOTAL_ registros', 
			'sInfoFiltered': '(filtrado de _MAX_ registros en total)', 
			'sPrevious': 'Anterior', 
			'sNext': 'Siguiente', 
			'sZeroRecords': 'Ningún registro encontrado', 
			'sInfoEmpty': 'Mostrando'
		}, 
		"print": {
			"sToolTip": "Vista de Impresion"
		}
	});
});*/


/********************************************DESHABILITACIÓN*******************************/
/*jQuery('#confirm-delete').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	var modal = jQuery(this);
	modal.find('.form').attr('action', recipient[2] + '/' + recipient[0]);
	modal.find('.delete-secure').text(recipient[1]);
});*/

/*jQuery('#details-show').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	jQuery.ajax({
		type: 'GET', 
		url: _site_url + recipient[1] + '/' + recipient[0], 
		dataType:'json', 
		error: function () {
			toastr.error("Se ha generado un error en el sistema. Por favor, contacte a su administrador.", "Mensaje del Sistema");
			console.log(_site_url + recipient[1] + '/' + recipient[0]);
		}, 
		success: function(response) {
			var valores = "";
			$.each(response, function(key, value) {
				valores = valores + value + "<br>";
			});
			modal.find('.show-secure').html(valores);
		}
	});
	var modal = jQuery(this);
});

jQuery('#confirm-update').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	jQuery.ajax({
		type: 'GET', 
		url: _site_url + recipient[3] + '/' + recipient[0], 
		data: {casino: recipient[2], empresa: recipient[1]}, 
		dataType:'json', 
		error: function () {
			toastr.error("Se ha generado un error en el sistema. Por favor, contacte a su administrador.", "Mensaje del Sistema");
		}, 
		success: function(response) {
			var valores = "";
			$.each(response, function(key, value) {
				valores = valores + value + "<br>";
			});
			modal.find('.show-secure').html(valores);
		}
	});
	var modal = jQuery(this);
});

jQuery('#confirm-edit').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	var modal = jQuery(this);
	modal.find('.form').attr('action', recipient[2] + '/' + recipient[0] + '/' + recipient[1]);
	modal.find('.edit-secure-action').text(recipient[1]);
	modal.find('.edit-secure').text(recipient[0]);
});

jQuery('#confirm-edit-persona').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	var modal = jQuery(this);
	modal.find('.form').attr('action', recipient[3] + '/' + recipient[0] + '/' + recipient[1]);
	modal.find('.edit-secure-action').text(recipient[1]);
	modal.find('.edit-secure').text(recipient[0]);
});

jQuery('#confirm-edit-empresa').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	var modal = jQuery(this);
	modal.find('.form').attr('action', recipient[3] + '/block/' + recipient[0] + '/' + recipient[1]);
	modal.find('.edit-secure-action').text(recipient[1]);
	modal.find('.edit-secure').text(recipient[2]);
});

jQuery('#confirm-block').on('show.bs.modal', function(e) {
	var button = jQuery(e.relatedTarget);
	var recipient = button.data('whatever').split('|');
	var modal = jQuery(this);
	modal.find('.form').attr('action', recipient[3] + '/block/' + recipient[0] + '/' + recipient[2]);
	modal.find('.block-action').text(recipient[2]);
	modal.find('.block-secure').text(recipient[1]);
});*/