jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR tipodocumento********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var tipodocumento = jQuery.trim(jQuery('form[name=agregar] input[name=tiposdocumentos]').val());
		var vigencia = jQuery('form[name=agregar] input[name=vigencia]').val();
        var duracion = jQuery('form[name=agregar] input[name=duracion]').val();
        var obligatoriedad = jQuery('form[name=agregar] input[name=obligatoriedad]').val();
        var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL tipodocumento ********************************/
		if (tipodocumento == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposdocumentos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del tipodocumento</p>');
		} else if (tipodocumento.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposdocumentos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">El nombre del tipodocumento debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipodocumento en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposdocumentos/existeTipoDocumento', 
				data: {tipodocumento: tipodocumento}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=tiposdocumentos]').addClass('error-border');
						jQuery('form[name=agregar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">El tipodocumento ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=tiposdocumentos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/********************VALIDACIÓN vigencia********************/
		if (vigencia == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=vigencia]').addClass('error-border');
			jQuery('form[name=agregar] select[name=vigencia]').focus().after('<p class="error-crud">Por favor, indique si tiene vigencia</p>');
		} else {
			jQuery('form[name=agregar] select[name=vigencia]').removeClass('error-border');
		}

		/********************VALIDACIÓN obligatoriedad*********************/
		if (obligatoriedad == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=obligatoriedad]').addClass('error-border');
			jQuery('form[name=agregar] select[name=obligatoriedad]').focus().after('<p class="error-crud">Por favor, indique si el documento es obligatorio</p>');
		} else {
			jQuery('form[name=agregar] select[name=obligatoriedad]').removeClass('error-border');
		}
		/*****************************************************************************************************/
		if (error_add) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	/********************************************MODIFICAR tipodocumento********************************************/
	jQuery('form[name=modificar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var url_form = jQuery('form[name=modificar]').attr('action');
		var url_split = url_form.split('/');
		var id_tipodocumento = url_split.pop();
		var tipodocumento = jQuery.trim(jQuery('form[name=modificar] input[name=tiposdocumentos]').val());
		var vigencia = jQuery('form[name=modificar] input[name=vigencia]').val();
        var duracion = jQuery('form[name=modificar] input[name=duracion]').val();
        var obligatoriedad = jQuery('form[name=modificar] input[name=obligatoriedad]').val();
		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL tipodocumento ********************************/
		if (tipodocumento == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposdocumentos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del tipodocumento</p>');
		} else if (tipodocumento.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposdocumentos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">El nombre del tipodocumento debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipodocumento en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposdocumentos/existetipodocumento', 
				data: {id_tipodocumento: id_tipodocumento, tipodocumento: tipodocumento}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=tiposdocumentos]').addClass('error-border');
						jQuery('form[name=modificar] input[name=tiposdocumentos]').focus().after('<p class="error-crud">El tipodocumento ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=tiposdocumentos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/********************VALIDACIÓN vigencia********************/
		if (vigencia == '') {
			error_upd = true;
			jQuery('form[name=modificar] select[name=vigencia]').addClass('error-border');
			jQuery('form[name=modificar] select[name=vigencia]').focus().after('<p class="error-crud">Por favor, indique si el documento tiene vigencia</p>');
		} else {
			jQuery('form[name=modificar] select[name=vigencia]').removeClass('error-border');
		}

		/********************VALIDACIÓN obligatoriedad*********************/
		if (obligatoriedad == '') {
			error_upd = true;
			jQuery('form[name=modificar] select[name=obligatoriedad]').addClass('error-border');
			jQuery('form[name=modificar] select[name=obligatoriedad]').focus().after('<p class="error-crud">Por favor, indique si el documento es obligatorio</p>');
		} else {
			jQuery('form[name=modificar] select[name=obligatoriedad]').removeClass('error-border');
		}
		/*****************************************************************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	
	/************************************HABILITAR / DESHABILITAR tipodocumento************************************/
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