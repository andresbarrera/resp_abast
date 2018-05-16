jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/*********************************************AGREGAR USUARIO*********************************************/
	jQuery('form[name=agregar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var nombres = jQuery.trim(jQuery('form[name=agregar] input[name=nombres]').val());
		var patapel = jQuery('form[name=agregar] input[name=patapel]').val();
		var perfil = jQuery('form[name=agregar] select[name=perfil]').val();
		var area = jQuery('form[name=agregar] select[name=area]').val();
		var regexp = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
		var error_add = false;

		/********************VALIDACIÓN USUARIO********************/
		if (nombres == '') {
			error_add = true;
			jQuery('form[name=agregar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=agregar] input[name=nombres]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de usuario</p>');
		} else if (nombres.length < 3) {
			error_add = true;
			jQuery('form[name=agregar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=agregar] input[name=nombres]').focus().after('<p class="error-crud">El nombre de usuario debe tener al menos 3 caracteres</p>');
		} else if (!nombres.match(regexp)) {
			error_add = true;
			jQuery('form[name=agregar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=agregar] input[name=nombres]').focus().after('<p class="error-crud">Por favor, ingrese un nombre de usuario válido</p>');
		} else {
			//Valida la existencia del usuario en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: base_url + 'usuarios/existeUsuario', 
				data: {nombres: nombres}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_add = true;
						jQuery('form[name=agregar] input[name=nombres]').addClass('error-border');
						jQuery('form[name=agregar] input[name=nombres]').focus().after('<p class="error-crud">El nombre de usuario ya existe en los registros</p>');
					} else {
						jQuery('form[name=agregar] input[name=nombres]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/********************VALIDACIÓN patapel********************/
		if (patapel == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=patapel]').addClass('error-border');
			jQuery('form[name=agregar] select[name=patapel]').focus().after('<p class="error-crud">Por favor, indique apellido paterno</p>');
		} else {
			jQuery('form[name=agregar] select[name=patapel]').removeClass('error-border');
		}

		/********************VALIDACIÓN PERFIL*********************/
		if (perfil == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=perfil]').addClass('error-border');
			jQuery('form[name=agregar] select[name=perfil]').focus().after('<p class="error-crud">Por favor, seleccione el perfil de usuario</p>');
		} else {
			jQuery('form[name=agregar] select[name=perfil]').removeClass('error-border');
		}
		/********************VALIDACIÓN AREA*********************/
		if (area == '') {
			error_add = true;
			jQuery('form[name=agregar] select[name=area]').addClass('error-border');
			jQuery('form[name=agregar] select[name=area]').focus().after('<p class="error-crud">Por favor, seleccione el perfil de usuario</p>');
		} else {
			jQuery('form[name=agregar] select[name=area]').removeClass('error-border');
		}
		/**********************************************************/
		if (error_add) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});
			return false;
		}
	});

	/********************************************MODIFICAR USUARIO********************************************/
	jQuery('form[name=modificar] input[name=guardar]').click(function() {
		jQuery('.error-crud').remove();
		var url_form = jQuery('form[name=modificar]').attr('action');
		var url_split = url_form.split('/');
		var id_usuario = url_split.pop();
		var nombres = jQuery.trim(jQuery('form[name=modificar] input[name=nombres]').val());
		var patapel = jQuery('form[name=modificar] select[name=patapel]').val();
		var perfil = jQuery('form[name=modificar] select[name=perfil]').val();
		var regexp = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
		var error_upd = false;

		/********************VALIDACIÓN USUARIO********************/
		if (nombres == '') {
			error_upd = true;
			jQuery('form[name=modificar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=modificar] input[name=nombres]').focus().after('<p class="error-crud">Por favor, ingrese el nombre de usuario</p>');
		} else if (nombres.length < 3) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=modificar] input[name=nombres]').focus().after('<p class="error-crud">El nombre de usuario debe tener al menos 3 caracteres</p>');
		} else if (!nombres.match(regexp)) {
			error_upd = true;
			jQuery('form[name=modificar] input[name=nombres]').addClass('error-border');
			jQuery('form[name=modificar] input[name=nombres]').focus().after('<p class="error-crud">Por favor, ingrese un nombre de usuario válido</p>');
		} else {
			//Valida la existencia del usuario en base de datos
			jQuery.ajax({
				type: 'POST', 
				url: baseurl + 'usuarios/existeUsuario', 
				data: {id_usuario: id_usuario, nombres: nombres}, 
				async: false, 
				success: function(data) {
					if (data > 0) {
						error_upd = true;
						jQuery('form[name=modificar] input[name=nombres]').addClass('error-border');
						jQuery('form[name=modificar] input[name=nombres]').focus().after('<p class="error-crud">El nombre de usuario ya existe en los registros</p>');
					} else {
						jQuery('form[name=modificar] input[name=nombres]').removeClass('error-border');
					}
				}, 
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		}

		/********************VALIDACIÓN patapel********************/
		if (patapel == '') {
			error_upd = true;
			jQuery('form[name=modificar] select[name=patapel]').addClass('error-border');
			jQuery('form[name=modificar] select[name=patapel]').focus().after('<p class="error-crud">Por favor, indique apellido paterno</p>');
		} else {
			jQuery('form[name=modificar] select[name=patapel]').removeClass('error-border');
		}

		/********************VALIDACIÓN PERFIL*********************/
		if (perfil == '') {
			error_upd = true;
			jQuery('form[name=modificar] select[name=perfil]').addClass('error-border');
			jQuery('form[name=modificar] select[name=perfil]').focus().after('<p class="error-crud">Por favor, seleccione el perfil de usuario</p>');
		} else {
			jQuery('form[name=modificar] select[name=perfil]').removeClass('error-border');
		}
		/********************VALIDACIÓN AREA*********************/
		if (area == '') {
			error_upd = true;
			jQuery('form[name=modificar] select[name=area]').addClass('error-border');
			jQuery('form[name=modificar] select[name=area]').focus().after('<p class="error-crud">Por favor, seleccione el perfil de usuario</p>');
		} else {
			jQuery('form[name=modificar] select[name=area]').removeClass('error-border');
		}
		/**********************************************************/
		if (error_upd) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});
			return false;
		}
	});

	/*******************************************MODIFICAR CONTRASEÑA******************************************/
	jQuery('form[name=changepassword] input[name=change]').click(function() {
		jQuery('.error-crud').remove();
		var password = jQuery('form[name=changepassword] input[name=password]').val().toLowerCase();
		var repassword = jQuery('form[name=changepassword] input[name=repassword]').val().toLowerCase();
		var error_chg = false;

		/****************************************VALIDACIÓN CONTRASEÑA****************************************/
		if (password == '') {
			error_chg = true;
			jQuery('form[name=changepassword] input[name=password]').addClass('error-border');
			jQuery('form[name=changepassword] input[name=password]').focus().after('<p class="error-crud">Por favor, ingrese su nueva contrase\u00F1a</p>');
		} else if (password.length < 6) {
			error_chg = true;
			jQuery('form[name=changepassword] input[name=password]').addClass('error-border');
			jQuery('form[name=changepassword] input[name=password]').focus().after('<p class="error-crud">Su contrase\u00F1a debe poseer m\u00EDnimo 6 caracteres</p>');
		}

		if (repassword == '') {
			error_chg = true;
			jQuery('form[name=changepassword] input[name=repassword]').addClass('error-border');
			jQuery('form[name=changepassword] input[name=repassword]').focus().after('<p class="error-crud">Por favor, reingrese su nueva contrase\u00F1a</p>');
		} else if (password != repassword) {
			error_chg = true;
			jQuery('form[name=changepassword] input[name=repassword]').addClass('error-border');
			jQuery('form[name=changepassword] input[name=repassword]').focus().after('<p class="error-crud">Las contrase\u00F1as no coinciden</p>');
		}
		/*****************************************************************************************************/
		if (error_chg) {
			toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});
			return false;
		}
	});
	/************************************HABILITAR / DESHABILITAR USUARIO************************************/
	jQuery('#confirm-delete').on('show.bs.modal', function(e) {
		var button = jQuery(e.relatedTarget);
		var url = button.data('href');
		var recipient = button.data('whatever').split('|');
		var modal = jQuery(this);
		modal.find('form[name=eliminar]').attr('action', url);
		modal.find('.delete-secure-action').text(recipient[1]);
		modal.find('.delete-title').text(recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1));
		modal.find('.delete-secure').text(recipient[0]);
	});

	/********************************************BLOQUEAR USUARIO*********************************************/
	jQuery('#confirm-block').on('show.bs.modal', function(e) {
		var button = jQuery(e.relatedTarget);
		var url = button.data('href');
		var recipient = button.data('whatever').split('|');
		var modal = jQuery(this);
		modal.find('form[name=bloquear]').attr('action', url);
		modal.find('.block-secure-action').text(recipient[1]);
		modal.find('.block-title').text(recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1));
		modal.find('.block-secure').text(recipient[0]);
	});

	
});