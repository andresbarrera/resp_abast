jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR articulo********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var articulo = jQuery.trim(jQuery('form[name=agregar] input[name=articulos]').val());
		var tipoarticulo = jQuery.trim(jQuery('form[name=agregar] select[name=tipoarticulo]').val());
		var error_add = false;

		/********************************VALIDACIÓN NOMBRE DEL articulo ********************************/
		if (articulo == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=articulos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=articulos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre del articulo</p>');
		} else if (articulo.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=articulos]').addClass('error-border');
			jQuery('form[name=agregar] input[name=articulos]').focus().after('<p class="error-crud">El nombre del articulo debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del articulo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'articulo/existeArticulo', 
				data: {articulo: articulo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=articulos]').addClass('error-border');
						jQuery('form[name=agregar] input[name=articulos]').focus().after('<p class="error-crud">El articulo ingresado ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=articulos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE ARTICULO*******************************/
		if (tipoarticulo == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=tipoarticulo]').addClass('error-border');
			jQuery('form[name=agregar] select[name=tipoarticulo]').focus().after('<p class="error-crud">Por favor, seleccione el tipo de articulo correspondiente</p>');
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
		var id_articulo = url_split.pop();
		var articulo = jQuery.trim(jQuery('form[name=modificar] input[name=articulos]').val());
		var tipoarticulo = jQuery.trim(jQuery('form[name=modificar] select[name=tipoarticulo]').val());
		var error_upd = false;

		/********************************VALIDACIÓN NOMBRE DEL articulo ********************************/
		if (articulo == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=articulos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=articulos]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de la familia</p>');
		} else if (articulo.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=articulos]').addClass('error-border');
			jQuery('form[name=modificar] input[name=articulos]').focus().after('<p class="error-crud">El nombre de la familia debe tener al menos 3 caracteres</p>');
		} else {
			//Valida la existencia del articulo en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'articulo/existeArticulo', 
				data: {id_articulo: id_articulo, articulo: articulo}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=articulos]').addClass('error-border');
						jQuery('form[name=modificar] input[name=articulos]').focus().after('<p class="error-crud">La familia ingresada ya se encuentra en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=articulos]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/*******************************VALIDACIÓN NOMBRE DEL TIPO DE ARTICULO*******************************/
		if (tipoarticulo == " ") {
			error_upd = true;
			jQuery('form[name=modificar] select[name=tipoarticulo]').addClass('error-border');
			jQuery('form[name=modificar] select[name=tipoarticulo]').focus().after('<p class="error-crud">Por favor, seleccione la articulo correspondiente</p>');
		}

		/*****************************************************************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información');
			return false;
		}
	});

	
	/************************************HABILITAR / DESHABILITAR articulo************************************/
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