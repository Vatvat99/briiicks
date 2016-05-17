function getSeriesFromRange(range_id) {
	if(range_id != '') {
        $( 'select#serie' ).empty(); // On vide la liste des départements
         
        $.ajax({
            url: '/admin/series/getSeriesFromRange',
            data: 'range_id='+ range_id, // On envoie $_GET['range_id']
            dataType: 'json',
            success: function(json) {
            	// Si il n'y a aucune série
            	if(json.length == 0)
            	{
            		// On affiche un message
            		$( 'select#serie' ).addClass('hidden');
            		$( 'p#no-serie').removeClass('hidden');
            		$( 'select#minifigures' ).addClass('hidden');
            		$( 'p#no-minifigure').removeClass('hidden');
            		$( 'p#no-minifigure' ).html('Veuillez sélectionner une série');
            	}
            	// Si on a trouvé des séries
            	else
            	{
            		// On remplit le select
            		$( 'select#serie' ).removeClass('hidden');
            		$( 'p#no-serie').addClass('hidden');
                    $.each(json, function(index, serie) {
                        $( 'select#serie' ).append('<option value="'+ serie['id'] +'">'+ serie['name'] +'</option>');
                    });

                    // Et on recherche les figurines contenues dans la première série
                    var serie_id = $( 'select#serie' ).val();
    				getMinifiguresFromSerie(serie_id);

            	}
            }
        });
    }
}

function getMinifiguresFromSerie(serie_id) {
	if(serie_id != '') {
        $( 'select#minifigures' ).empty(); // On vide la liste des figurines
         
        $.ajax({
            url: '/admin/minifigures/getMinifiguresFromSerie',
            data: 'serie_id='+ serie_id, // On envoie $_GET['serie_id']
            dataType: 'json',
            success: function(json) {
            	if(json.length == 0)
            	{
            		$( 'select#minifigures' ).addClass('hidden');
            		$( 'p#no-minifigure').removeClass('hidden');
            		$( 'p#no-minifigure' ).html('Aucune figurine dans cette série (<a href="index.php?page=minifigures-add">en ajouter une</a>)');
            	}
            	else
            	{
            		$( 'select#minifigures' ).removeClass('hidden');
            		$( 'p#no-minifigure').addClass('hidden');
                    $.each(json, function(index, minifigure) {
                        $( 'select#minifigures' ).append('<option value="'+ minifigure['id'] +'">'+ minifigure['name'] +'</option>');
                    });
            	}
            }
        });
    }
}

// Quand le DOM est chargé
$( document ).ready(function() {

	// Initialisation
	var theHREF; // Contient la cible du lien qui ouvre la pop-up

	// Lorsque l'on clique sur un lien de suppression
	$( 'a.delete-link' ).on('click', function(e) {

		e.preventDefault();

		theHREF = $(this).attr( 'href' );

		// Mise à jour du contenu de la pop-up
		$( '.delete-dialog td.name' ).text($(this).parent().siblings('.name').text());
		$( '.delete-dialog td.number' ).text($(this).parent().siblings('.number').text());

		// Faire apparaitre la pop-up et ajouter le bouton de fermeture
		$( '.delete-dialog' ).fadeIn(100).css({ 'max-width': '460'});

		var popWidth = $( '.delete-dialog' ).width(); //Trouver la largeur
		var popHeight = $( '.delete-dialog' ).height(); //Trouver la largeur
		var screenHeight = $(window).height();
		var screenWidth = $(window).width();
		
		// Récupération du margin, qui permettra de centrer la fenêtre
		var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();
		var popMargLeft = screenWidth / 2 - popWidth / 2;
		
		// Apply Margin to Popup
		$( '.delete-dialog' ).css({ 
			'margin-top' : popMargTop,
			'margin-left' : popMargLeft
		});
		
		// Apparition du fond - .css({'filter' : 'alpha(opacity=50)'}) pour corriger les bogues d'anciennes versions de IE
		$('body').append('<div id="overlay" class="overlay"></div>');
		$('#overlay').css({'filter' : 'alpha(opacity=50)'}).fadeIn(100);

		$( window ).resize(function() {
			var popWidth = $( '.delete-dialog' ).width();
			var popHeight = $( '.delete-dialog' ).height();
			var screenHeight = $(window).height();
			var screenWidth = $(window).width();

			var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();
			var popMargLeft = screenWidth / 2 - popWidth / 2;

			// Apply Margin to Popup
			$( '.delete-dialog' ).css({ 
				'margin-top' : popMargTop,
				'margin-left' : popMargLeft
			});
		});

		$( window ).scroll(function() {
			var popHeight = $( '.delete-dialog' ).height();
			var screenHeight = $(window).height();

			var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();

			//Apply Margin to Popup
			$( '.delete-dialog' ).css({ 
				'margin-top' : popMargTop
			});
		});
		
		return false;
	});

	// Gestion des boutons
	$( 'body' ).on('click', '.delete-dialog a.delete', function(e) {
		e.preventDefault();
		window.location.href = theHREF;
	});

	$( 'body' ).on('click', '.delete-dialog a.cancel, .delete-dialog a.close, #overlay', function(e) {
		e.preventDefault();
		$('#overlay , .delete-dialog').fadeOut(100, function() {
			$('#overlay').remove();
		});
	});

	// Add : Affichage du select séries
	$( 'body' ).on( 'change', 'select#range', function(e) {
        var range_id = $( this ).val();
 		getSeriesFromRange(range_id);
	});

	// Add : Affichage du select figurines
	$( 'body' ).on( 'change', 'select#serie', function(e) {
        var serie_id = $( this ).val();
        getMinifiguresFromSerie(serie_id);
	});

});