jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
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
});