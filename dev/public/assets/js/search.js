$( document ).ready(function() {

	var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

	// Ouverture du panneau latéral
	/* $('#right-lateral-panel-link').click(function() {
		$('#content').addClass('to-left');
		$('#right-lateral-panel').addClass('open');
		$('#navigation').addClass('open');
    	$( 'body' ).append( '<div id="search-overlay" class="overlay"></div>' );
		$( '#search-overlay' ).css({'filter' : 'alpha(opacity=50)'}).fadeIn(100);
	}); */
	// Fermeture du panneau latéral
	/* $( document ).click( function(e) {
	    if( !$(e.target).closest( '#right-lateral-panel, #right-lateral-panel-link' ).length || $(e.target).closest( '#right-lateral-panel-close' ).length) {
	        if( $('#right-lateral-panel').hasClass('open') ) {
	            $('#content').removeClass('to-left');
				$('#right-lateral-panel').removeClass('open');
				$('#navigation').removeClass('open');
	        }
	        $( '#search-overlay' ).fadeOut(100, function() {
	            $( '#search-overlay' ).remove();
	        });
	       
	    }        
	}); */

    ////////////////////////////////////////////////////////
	// AJOUT D'UN ELEMENT A LA COLLECTION
    ////////////////////////////////////////////////////////
    // Ouverture du panneau "nombre d'exemplaires"
	$( '.item .add-link' ).click(function(e) {
		e.preventDefault();
		$( '.item-number' ).removeClass( 'active' );
		$( this ).parents( '.item-container' ).find( '.item-number' ).addClass( 'active' );
	});
    // fermeture du panneau "nombre d'exemplaires"
	$( document ).click( function(e) { 
	    if( !$(e.target).closest( '.item-number.active, .item .add-link' ).length) {
 			$( '.item-number' ).removeClass( 'active' );
            $( '.item-number-content' ).delay(animationDuration).removeClass( 'hidden' );
            $( '.item-number .item-confirmation-content' ).delay(animationDuration).addClass( 'hidden' );
	    }
	});
    // Modification du nombre d'exemplaire
    $( '.item-number .less-item-button' ).click(function(e) {
        var inputContainer = $(this).parents( '.input-container' );
        var itemNumberInput = inputContainer.find( '.item-number-input' );
        if(parseInt(itemNumberInput.val(), 10) > 1)
        {
            itemNumberInput.val(parseInt(itemNumberInput.val(), 10) - 1);
        }
    });
    $( '.item-number .more-item-button' ).click(function(e) {
        var inputContainer = $(this).parents( '.input-container' );
        var itemNumberInput = inputContainer.find( '.item-number-input' );
        itemNumberInput.val(parseInt(itemNumberInput.val(), 10) + 1);
    });
    // Ajout à la collection
    $( '.item-number .add-button' ).click(function(e) {
        e.preventDefault();
        var itemId = $(this).siblings( '.input-container').find( '.item-id-input' ).val();
        var itemType = $(this).siblings( '.input-container').find( '.item-type-input' ).val();
        var countId = $(this).siblings( '.input-container' ).find( '.item-number-input' ).val();
        var numberPanel = $(this).parents( '.item-container' ).find( '.item-number-content' );
		var itemContainer = $(this).parents( '.item-container' );
        var confirmationPanel = $(this).parents( '.item-container' ).find( '.item-number .item-confirmation-content' );
        $.ajax({
            url: '/collection/add',
            data: 'item_id=' + itemId + '&item_count=' + countId + '&item_type=' + itemType,
            dataType: 'json',
            success: function(json) {
                // Si l'utilisateur n'est pas connecté
                if(json == false) {
                    // On le redirige vers la page de login
                    window.location.href = '/members/login';
                }
                // Si l'utilisateur est connecté et qu'on a bien ajouté l'élément à la collection
                else {
                    // On affiche le panneau de confirmation
                    numberPanel.addClass( 'hidden' );
                    confirmationPanel.removeClass( 'hidden' );
					// Et on affiche le nombre d'élément désormais présent dans la collection
					if(itemContainer.find( '.item-count').length) {
						var previousCount = itemContainer.find( '.item-count' ).text().replace('x', '');
						var newCount = parseInt(previousCount) + parseInt(countId);
						console.log(newCount);
						itemContainer.find( '.item-count' ).text( 'x' + newCount);
					} else {
						itemContainer.find( '.item').prepend('<div class="item-count">x' + countId + '</div>');
					}
                }
            }
        });
    });

	////////////////////////////////////////////////////////
	// GESTION DES LISTES DES GAMMES ET DES SERIES
	////////////////////////////////////////////////////////

	// Quand on sélectionne une gamme
	$('select#selected-range-alias').change(function() {
		// On vide la valeur du champ caché des série
		$('input#selected-serie-alias').val('none');
		// On affiche la liste des séries correspondantes
		$('label.series-list').css({'display' : 'none'});
		$('select#series-list').hide();
		$('label.series-list.' + ($('#selected-range-alias').val())).css({'display' : 'inline-block'});
		$('#series-list.' + ($('#selected-range-alias').val())).show();
	});

	// Quand on sélectionne une série
	$('select#series-list').change(function() {
		// On passe la valeur au champ caché
		$('input#selected-serie-alias').val($(this).val());
	});

});