jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR tipovehiculo********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var tipovehiculo = jQuery.trim(jQuery('form[name=agregar] input[name=tiposvehiculos]').val());
		var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL tipovehiculo ********************************/
		if (tipovehiculo == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposvehiculos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del tipovehiculo</p>');
		} else if (tipovehiculo.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposvehiculos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">El nombre del tipovehiculo debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipovehiculo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposvehiculos/existeTipoVehiculo', 
				data: {tipovehiculo: tipovehiculo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=tiposvehiculos]').addClass('error-border');
						jQuery('form[name=agregar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">El tipovehiculo ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=tiposvehiculos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*****************************************************************************************************/
		if (error_add) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	/********************************************MODIFICAR tipovehiculo********************************************/
	jQuery('form[name=modificar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var url_form = jQuery('form[name=modificar]').attr('action');
		var url_split = url_form.split('/');
		var id_tipovehiculo = url_split.pop();
		var tipovehiculo = jQuery.trim(jQuery('form[name=modificar] input[name=tiposvehiculos]').val());
		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL tipovehiculo ********************************/
		if (tipovehiculo == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposvehiculos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del tipovehiculo</p>');
		} else if (tipovehiculo.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposvehiculos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">El nombre del tipovehiculo debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipovehiculo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposvehiculos/existeTipoVehiculo', 
				data: {id_tipovehiculo: id_tipovehiculo, tipovehiculo: tipovehiculo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=tiposvehiculos]').addClass('error-border');
						jQuery('form[name=modificar] input[name=tiposvehiculos]').focus().after('<p class="error-crud">El tipovehiculo ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=tiposvehiculos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*****************************************************************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	
	/************************************HABILITAR / DESHABILITAR tipovehiculo************************************/
	jQuery('#confirm-delete').on('show.bs.modal', function(e) {
		var button = jQuery(e.relatedTarget);
		var url = button.data('href');
		var recipient = button.data('whatever').split('|');
		var modal = jQuery(this);
		modal.find('form[name=eliminar]').attr('action', url);
		modal.find('.block-secure-action').text(recipient[1]);
		modal.find('.block-title').text(recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1));
		modal.find('form[name=eliminar] input[name=deshabilitar]').attr('value', (recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1)));
		modal.find('.block-secure').text(recipient[0]);
	});
});