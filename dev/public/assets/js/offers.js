/**
 * Affiche une figurine/set à insérer dans l'annonce
 * @param json item Informations sur l'éléments
 * @param string itemType Type de l'élément (minifigure/set)
 */
function displayItemBox(item, itemType)
{
	var newElement = $('.item-box:last').clone().removeClass('hidden');
	$('input[type="hidden"]', newElement).attr('name', itemType + 's[]').val(item.id);
	$('select', newElement).attr('name', itemType + 's_count[]');
	$('.picture', newElement).attr('src', '/assets/img/' + itemType + 's/57x57/' + item.picture);
	$('.name', newElement).text(item.name);
	$('.range', newElement).text(item.range_name);
	$('.serie', newElement).text(item.serie_name);
	$('.item-box-container.' + itemType).append(newElement);
}

/**
 * Affiche / masque le champ prix suivant le type de l'annnonce (vente ou échange)
 */
function displayPriceField() {

	if(!$('#type-sell').prop('checked') && $('#type-exchange').prop('checked')) {
		$('#price, label[for="price"]').hide();
	} else {
		$('#price, label[for="price"]').show();
	}

}

// Quand le DOM est chargé
$( document ).ready(function() {

	/*
	 * Vue d'une annonce
	 * gestion des sliders
	 */
	if($('.offers-view').length) {
		$('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.slider-navigation'
		});
		$('.slider-navigation').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			focusOnSelect: true
		});
	}

	/**
	 * Ajout / Edition d'une annonce
	 * Gestion de la sélection des sets / minifigures et de l'upload de photos
	 */
	if($('.offers-add').length || $('.offers-edit').length) {

		// Affichage / masquage du prix suivant le type de l'annonce
		displayPriceField();

		$('#type-sell, #type-exchange').on('change', function() {
			displayPriceField();
		});

		// Ajout des figurines/sets
		$('#minifigures').on('change', function(e) {
			$.ajax({
			url: '/offers/find',
			data: 'id=' + $(this).val() + '&type=minifigure',
			dataType: 'json',
			success: function(json) {
					if(json !== false) {
						// On désactive l'option du select
						$('#minifigures').find('option[value="' + json.id + '"]').prop('disabled', true);
						// On affiche la figurine
						displayItemBox(json, 'minifigure');
						// On réinitialise le select
						$('#minifigures').val('');
					}
				}
			});
		});

		$('#sets').on('change', function(e) {
			$.ajax({
				url: '/offers/find',
				data: 'id=' + $(this).val() + '&type=set',
				dataType: 'json',
				success: function(json) {
					if(json !== false) {
						// On désactive l'option du select
						$('#sets').find('option[value="' + json.id + '"]').prop('disabled', true);
						// On affiche le set
						displayItemBox(json, 'set');
						// On réinitialise le select
						$('#sets').val('');
					}
				}
			});
		});

		// Suppression d'une figurine / set
		$('body').on('click', '.item-box .action', function() {
			var id = $(this).parents('.item-box').find('input[type="hidden"]').val();
			var name = $(this).parents('.item-box').find('input[type="hidden"]').attr('name').replace('[]', '');
			$('#' + name).find('option[value="' + id + '"]').prop('disabled', false);
			$(this).parents('.item-box').remove();
		});

		// Ajout de plusieurs photos
		$('.add-picture-link').on('click', function(e) {
			e.preventDefault();
			var newElement = $('.input-file-container:last').clone();
			var input = newElement.find('.picture');
			var id = parseInt( input.prop('id').match(/\d+/g), 10 ) + 1;

			var pictures = $('input[type=checkbox].delete-picture').length;
			var picturesToDelete = $('input[type=checkbox].delete-picture:checked').length;
			var remainingPictures = pictures - picturesToDelete;

			if(id < (4 - remainingPictures)) {
				$('.picture', newElement).attr('id', 'picture-' + id);
				$('.input-file-return', newElement).val('');
				$(this).parents().siblings('.input-file-container:last').after(newElement);
				$( '.input-file-container:last').each( function() {
					customInputFile( $( this ) );
				});
			}
			if(id == (3 - remainingPictures)) {
				$(this).parents('p').hide();
			}
		});

		if($('input[type=checkbox].delete-picture').length) {

			$('input[type=checkbox].delete-picture').on('change', function() {
				var maxPictures = 4;
				// je compte le nombre de cases
				var pictures = $('input[type=checkbox].delete-picture').length;
				// je compte les cases cochées
				var picturesToDelete = $('input[type=checkbox].delete-picture:checked').length;
				var remainingPictures = pictures - picturesToDelete;
				var uploadablePictures = maxPictures - remainingPictures;
				// je compte le nombre de champs d'uploads
				var inputFields = $('input.picture').length;

				// Si il y a 4 images et aucune case cochée
				if(pictures == 4 && picturesToDelete == 0) {
					// Je cache le champ et le lien
					$('.add-picture-container').addClass('hidden');
				} else {
					// J'affiche le champ et le lien
					$('.add-picture-container').removeClass('hidden');
				}

				// Si le nombre de champs est supérieur au nombre d'images uploadables
				if(inputFields > uploadablePictures) {
					if($( '.input-file-container').length == 1) {
						$( '.input-file-container').hide();
					} else {
						// Je supprime le dernier champ
						$('.input-file-container:last').remove();
					}
				}

				// Si le nombre de champs est inférieur au nombre max d'images uploadables
				if (inputFields == uploadablePictures) {
					if($( '.input-file-container').length == 1) {
						$( '.input-file-container').show();
					}
					if(pictures == maxPictures && picturesToDelete == 1) {
						$('#addPictureLink').hide();
					}
				} else if (inputFields < uploadablePictures) {
					// J'affiche le lien pour ajouter des champs
					$('#addPictureLink').show();
				} else {
					$('#addPictureLink').hide();
				}


			});

		}

	}

});

// Quand tout est chargé (DOM + images)
$(window).load(function() {

});