$( document ).ready(function() {

	// Gestion du menu sur petits écrans

    $( 'body' ).on('click', '#navigation-button.inactive', function(e) {

        $( '#navigation-button' ).removeClass( 'inactive' );
        $( '#navigation-button' ).addClass( 'active' );

        $( '#navigation-button-container' ).addClass( 'active' );
        $( '#mobile-navigation' ).addClass( 'active' );

        $( 'body' ).append( '<div id="mobile-navigation-overlay" class="mobile-navigation-overlay"></div>' );
        $( '#mobile-navigation-overlay' ).css({'filter' : 'alpha(opacity=50)'}).fadeIn(100);

    });

    $( 'body' ).on('click', '#navigation-button.active, #mobile-navigation-overlay', function(e) {

        $( '#navigation-button' ).removeClass( 'active' );
        $( '#navigation-button' ).addClass( 'inactive' );

        $( '#navigation-button-container' ).removeClass( 'active' );
        $( '#mobile-navigation' ).removeClass( 'active' );

        $( '#mobile-navigation-overlay' ).fadeOut(100, function() {
            $( '#mobile-navigation-overlay' ).remove();
        });
    });

    $( 'body' ).on('click', '#mobile-navigation > li.inactive', function(e) {

    	$( '#mobile-navigation > li' ).removeClass( 'active' );
    	$( '#mobile-navigation > li' ).addClass( 'inactive' );
    	$( this ).removeClass( 'inactive' );
        $( this ).addClass( 'active' );

     	$( '#mobile-navigation ul' ).removeClass( 'active' );
     	$( this ).children('ul').addClass( 'active' );

    });

    $( 'body' ).on('click', '#mobile-navigation > li.active', function(e) {

    	$( this ).removeClass( 'active' );
        $( this ).addClass( 'inactive' );

     	$( '#mobile-navigation ul' ).removeClass( 'active' );

    });


    // Gestion du menu sur grands écrans

	$( '#desktop-navigation .first-level ul li a' ).click(function(event) {
		if(!$( this ).parent().hasClass('home'))
		{
			event.preventDefault();

			if( $(this).hasClass('active'))
			{
				// On cache le menu
				$( '.container' ).removeClass( 'navigation-opened' );
				$( '#desktop-navigation .second-level' ).each(function() {
					$( this ).removeClass( 'navigation-open' );
				});
				$( '#desktop-navigation .first-level ul li a' ).each(function() {
					$( this ).removeClass( 'active' );
					$( this ).removeClass( 'opened' );
				});
			}
			else {

				$( '#desktop-navigation .first-level ul li a' ).each(function() {
					$( this ).removeClass( 'active' );
					$( this ).addClass( 'opened' );
				});

				$( this ).addClass( 'active' );

				var page = $( this ).parent().attr( 'class' );
				// console.log(page);

				$( '#desktop-navigation .second-level' ).each(function() {
					$( this ).removeClass( 'navigation-open' );

					if ($( this ).hasClass( page )) 
					{
						$( this ).addClass( 'navigation-open' );
					}
				});

				$( '.container' ).addClass( 'navigation-opened' );
			}
		}

	});

	$(document).on('click', function(event) {
		// Si le clic est en dehors du menu, ou sur le lien actif
		if (!$(event.target).closest('#desktop-navigation').length) {
			// On cache le menu
			$( '.container' ).removeClass( 'navigation-opened' );
			$( '#desktop-navigation .second-level' ).each(function() {
				$( this ).removeClass( 'navigation-open' );
			});
			$( '#desktop-navigation .first-level ul li a' ).each(function() {
				$( this ).removeClass( 'active' );
				$( this ).removeClass( 'opened' );
			});
		}
	});

});