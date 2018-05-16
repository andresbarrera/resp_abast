jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR AREA********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var area = jQuery.trim(jQuery('form[name=agregar] input[name=areas]').val());
		var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL AREA ********************************/
		if (area == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=areas]').addClass('error-border');
			jQuery('form[name=agregar] input[name=areas]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del area</p>');
		} else if (area.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=areas]').addClass('error-border');
			jQuery('form[name=agregar] input[name=areas]').focus().after('<p class="error-crud">El nombre del area debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del area en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'areas/existeArea', 
				data: {area: area}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=areas]').addClass('error-border');
						jQuery('form[name=agregar] input[name=areas]').focus().after('<p class="error-crud">El area ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=areas]').removeClass('error-border');
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

	/********************************************MODIFICAR AREA********************************************/
	jQuery('form[name=modificar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var url_form = jQuery('form[name=modificar]').attr('action');
		var url_split = url_form.split('/');
		var id_area = url_split.pop();
		var area = jQuery.trim(jQuery('form[name=modificar] input[name=areas]').val());
		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL AREA ********************************/
		if (area == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=areas]').addClass('error-border');
			jQuery('form[name=modificar] input[name=areas]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del area</p>');
		} else if (area.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=areas]').addClass('error-border');
			jQuery('form[name=modificar] input[name=areas]').focus().after('<p class="error-crud">El nombre del area debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del area en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'areas/existeArea', 
				data: {id_area: id_area, area: area}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=areas]').addClass('error-border');
						jQuery('form[name=modificar] input[name=areas]').focus().after('<p class="error-crud">El area ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=areas]').removeClass('error-border');
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

	
	/************************************HABILITAR / DESHABILITAR AREA************************************/
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