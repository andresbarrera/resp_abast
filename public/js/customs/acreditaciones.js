jQuery(document).ready(function() {
	// CSRF protection
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('input[name="_token"]').val()
		}
	});

	/************************************HABILITAR / DESHABILITAR articulo************************************/
	jQuery('#confirm-aprueba').on('show.bs.modal', function(e) {
		var button = jQuery(e.relatedTarget);
		var url = button.data('href');
		var recipient = button.data('whatever').split('|');
		var modal = jQuery(this);
		modal.find('form[name=acreditar]').attr('action', url);
		modal.find('.block-secure-action').text(recipient[1]);
		modal.find('.block-title').text(recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1));
		modal.find('form[name=acreditar] input[name=acreditar]').attr('value', (recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1)));
		modal.find('.block-secure').text(recipient[0]);
	});

	jQuery('#confirm-rechaza').on('show.bs.modal', function(e) {
		var button = jQuery(e.relatedTarget);
		var url = button.data('href');
		var recipient = button.data('whatever').split('|');
		var modal = jQuery(this);
		modal.find('form[name=rechazar]').attr('action', url);
		modal.find('.block-secure-action').text(recipient[1]);
		modal.find('.block-title').text(recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1));
		modal.find('form[name=rechazar] input[name=rechazar]').attr('value', (recipient[1].charAt(0).toUpperCase() + recipient[1].slice(1)));
		modal.find('.block-secure').text(recipient[0]);
	});
});