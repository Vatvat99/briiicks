$( document ).ready(function() {

	$('input#email').focusin(function() {
		// On vide le champ si c'est la valeur par défaut
		if($(this).val() == 'Adresse e-mail') {
			$(this).val('');
		}
	});
	$('input#email').focusout(function() {
		// On remet la valeur par défaut si le champ est vide
		if($(this).val() == '') {
			$(this).val('Adresse e-mail');
		}
	});

	$('input#password').focusin(function() {
		// On vide le champ si c'est la valeur par défaut
		if($(this).val() == 'Mot de passe') {
			$(this).val('');
		}
	});
	$('input#password').focusout(function() {
		// On remet la valeur par défaut si le champ est vide
		if($(this).val() == '') {
			$(this).val('Mot de passe');
		}
	});
});