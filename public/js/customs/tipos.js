jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR tipoarticulo********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var tipoarticulo = jQuery.trim(jQuery('form[name=agregar] input[name=tiposarticulos]').val());
		var aprobacion = jQuery.trim(jQuery('form[name=agregar] select[name=aprobacion]').val());
		var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL tipoarticulo ********************************/
		if (tipoarticulo == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposarticulos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposarticulos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de la familia</p>');
		} else if (tipoarticulo.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=tiposarticulos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=tiposarticulos]').focus().after('<p class="error-crud">El nombre de la familia debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipoarticulo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposarticulos/existeTipoArticulo', 
				data: {tipoarticulo: tipoarticulo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=tiposarticulos]').addClass('error-border');
						jQuery('form[name=agregar] input[name=tiposarticulos]').focus().after('<p class="error-crud">La familia ingresada ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=tiposarticulos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL PROYECTO ASOCIADO*******************************/
		if (aprobacion == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=aprobacion]').addClass('error-border');
			jQuery('form[name=agregar] select[name=aprobacion]').focus().after('<p class="error-crud">Por favor, seleccione la aprobacion correspondiente</p>');
		}

		/*****************************************************************************************************/
		if (error_add) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	/********************************************MODIFICAR USUARIO********************************************/
	jQuery('form[name=modificar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var url_form = jQuery('form[name=modificar]').attr('action');
		var url_split = url_form.split('/');
		var id_tipoarticulo = url_split.pop();
		var tipoarticulo = jQuery.trim(jQuery('form[name=modificar] input[name=tiposarticulos]').val());
		var aprobacion = jQuery.trim(jQuery('form[name=modificar] select[name=aprobacion]').val());
		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL tipoarticulo ********************************/
		if (tipoarticulo == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposarticulos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposarticulos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de la familia</p>');
		} else if (tipoarticulo.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=tiposarticulos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=tiposarticulos]').focus().after('<p class="error-crud">El nombre de la familia debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del tipoarticulo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'tiposarticulos/existeTipoArticulo', 
				data: {id_tipoarticulo: id_tipoarticulo, tipoarticulo: tipoarticulo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=tiposarticulos]').addClass('error-border');
						jQuery('form[name=modificar] input[name=tiposarticulos]').focus().after('<p class="error-crud">La familia ingresada ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=tiposarticulos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL PROYECTO ASOCIADO*******************************/
		if (aprobacion == "selected") {
			error_upd = true;
			jQuery('form[name=modificar] select[name=aprobacion]').addClass('error-border');
			jQuery('form[name=modificar] select[name=aprobacion]').focus().after('<p class="error-crud">Por favor, seleccione la aprobacion correspondiente</p>');
		}

		/*****************************************************************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	
	/************************************HABILITAR / DESHABILITAR tipoarticulo************************************/
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