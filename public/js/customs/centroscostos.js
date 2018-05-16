jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR centrocosto********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var centrocosto = jQuery.trim(jQuery('form[name=agregar] input[name=centroscostos]').val());
		var fechainicio = jQuery.trim(jQuery('form[name=agregar] select[name=fechainicio]').val());
		var fechafinal = jQuery.trim(jQuery('form[name=agregar] select[name=fechafinal]').val());
		var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL centrocosto ********************************/
		if (centrocosto == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=centroscostos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=centroscostos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del centrocosto</p>');
		} else if (centrocosto.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=centroscostos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=centroscostos]').focus().after('<p class="error-crud">El nombre del centrocosto debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del centrocosto en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'centroscostos/existeCentroCosto', 
				data: {centrocosto: centrocosto}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=centroscostos]').addClass('error-border');
						jQuery('form[name=agregar] input[name=centroscostos]').focus().after('<p class="error-crud">El centrocosto ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=centroscostos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE centrocosto*******************************/
		if (fechainicio == '') {
	
			error_add = true;
			jQuery('form[name=agregar] select[name=fechainicio]').addClas
			s('error-border');
			jQuery('form[name=agregar] select[name=fechainicio]').focus()
			.after('<p class="error-crud">Por favor, seleccione el tipo de centrocosto correspondiente</p>');
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE centrocosto*******************************/
		if (fechafinal == '') {
	
			error_add = true;
			jQuery('form[name=agregar] select[name=fechafinal]').addClas
			s('error-border');
			jQuery('form[name=agregar] select[name=fechafinal]').focus()
			.after('<p class="error-crud">Por favor, seleccione el tipo de centrocosto correspondiente</p>');
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
		var id_centrocosto = url_split.pop();
		var centrocosto = jQuery.trim(jQuery('form[name=modificar] input[name=centroscostos]').val());
		var fechainicio = jQuery.t
		rim(jQuery('form[name=modificar] select[name=fechainicio]').val());

		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL centrocosto ********************************/
		if (centrocosto == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=centroscostos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=centroscostos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de la familia</p>');
		} else if (centrocosto.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=centroscostos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=centroscostos]').focus().after('<p class="error-crud">El nombre de la familia debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del centrocosto en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'centrocosto/existeCentroCosto', 
				data: {id_centrocosto: id_centrocosto, centrocosto: centrocosto}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=centroscostos]').addClass('error-border');
						jQuery('form[name=modificar] input[name=centroscostos]').focus().after('<p class="error-crud">La familia ingresada ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=centroscostos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE centrocosto*******************************/
		if (fechainicio == " ") {

			error_upd = true;
			jQuery('form[name=modificar] select[name=fechainicio]').addClas
			s('error-border');
			jQuery('form[name=modificar] select[name=fechainicio]').focus()
			.after('<p class="error-crud">Por favor, seleccione la centrocosto correspondiente</p>');
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE centrocosto*******************************/
		if (fechafinal == " ") {

			error_upd = true;
			jQuery('form[name=modificar] select[name=fechafinal]').addClas
			s('error-border');
			jQuery('form[name=modificar] select[name=fechafinal]').focus()
			.after('<p class="error-crud">Por favor, seleccione la centrocosto correspondiente</p>');
		}

		/*****************************************************************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	
	/************************************HABILITAR / DESHABILITAR centrocosto************************************/
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