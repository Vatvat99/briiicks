var animationDuration = 200;

/**
 * Redimensionne des images à la taille de leur conteneur
 * @param cssClass Classe des images à redimensionner
 * @param fitAll Indique si oui ou non l'image doit occuper tout le conteneur quitte à être recadrée (true)
 * ou si elle doit être entièrement visible quitte à ne pas occuper tout le conteneur (false)
 */
function resizePictureToContainer( cssClass, fitAll ){

    $( 'img.' + cssClass ).each( function() {

        var pictureWidth = $( this ).width();
        var pictureHeight = $( this ).height();

        var over = pictureWidth / pictureHeight;
        var under = pictureHeight / pictureWidth;

        var containerWidth = $( this ).parent().width();
        var containerHeight = $( this ).parent().height();

        if(fitAll == true) {
            if (containerWidth / containerHeight >= over) {
                $(this).css({
                    'width': containerWidth + 'px',
                    'height': Math.ceil(under * containerWidth) + 'px',
                    'left': '0px',
                    'top': Math.abs((under * containerWidth) - containerHeight) / -2 + 'px'
                });
            }
            else {
                $(this).css({
                    'width': Math.ceil(over * containerHeight) + 'px',
                    'height': containerHeight + 'px',
                    'top': '0px',
                    'left': Math.abs((over * containerHeight) - containerWidth) / -2 + 'px'
                });
            }
        } else {
            if (containerWidth / containerHeight >= over) {
                $(this).css({
                    'width': Math.ceil(over * containerHeight) + 'px',
                    'height': containerHeight + 'px',
                    'top': '0px',
                    'left': Math.abs((over * containerHeight) - containerWidth) / 2 + 'px'
                });
            }
            else {
                $(this).css({
                    'width': containerWidth + 'px',
                    'height': Math.ceil(under * containerWidth) + 'px',
                    'top': Math.abs((under * containerWidth) - containerHeight) / 2 + 'px',
                    'left': '0px'
                });
            }
        }

    });
}

/**
 * Calcul l'affichage du graphique de répartition par gamme (pages collection / profil)
 */
function displayChart()
{
    var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    if(windowWidth < 640)
    {
        // Version mobile
        $( '.chart-segment').each(function() {
            var chartBarWidth = $( this).data( 'width' )/2 + '%';
            $( this).css( 'width', 'auto');
            $( this).find( '.chart-bar').css( 'width', chartBarWidth);
        });
    }
    else
    {
        // Version desktop
        $( '.chart-segment').each(function() {
            var chartBarWidth = $( this).data( 'width' ) + '%';
            $( this).css( 'width', chartBarWidth);
            $( this).find( '.chart-bar').css( 'width', 'auto');
        });
    }
}

/**
 * Modifie l'apparence des input[type="file"]
 */
function customInputFile(inputContainer)
{
    var fileInput  = inputContainer.find( 'input[type="file"]' ),
        button     = inputContainer.find( 'button' ),
        the_return = inputContainer.find( 'input[type="text"]' );

    button.on( 'keydown', function( event ) {
        if ( event.keyCode == 13 || event.keyCode == 32 ) {
            fileInput.click();
            return false;
        }
    });

    button.on( 'click', function( event ) {
        console.log('plop');
        fileInput.click();
        return false;
    });

    fileInput.on( 'change', function( event ) {
        var file_name_pieces = $( this ).val().split('/');
        var file_name_pieces = $( this ).val().split('\\');
        var file_name = file_name_pieces[file_name_pieces.length - 1];
        the_return.val( file_name );
    });
}

// Quand le DOM est chargé
$( document ).ready(function() {

    // Gestion du menu sur petits écrans

    $( 'body' ).on('click', '#navigation-button.inactive', function(e) {

        $( '#navigation-button' ).removeClass( 'inactive' );
        $( '#navigation-button' ).addClass( 'active' );

        $( '#navigation' ).addClass( 'active' );

        $( 'body' ).append( '<div class="navigation-overlay overlay"></div>' );
        $( '.navigation-overlay' ).css({'filter' : 'alpha(opacity=50)'}).fadeIn(100);

    });

    $( 'body' ).on('click', '#navigation-button.active, .navigation-overlay', function(e) {

        $( '#navigation-button' ).removeClass( 'active' );
        $( '#navigation-button' ).addClass( 'inactive' );

        $( '#navigation' ).removeClass( 'active' );

        $( '.navigation-overlay' ).fadeOut(100, function() {
            $( '.navigation-overlay' ).remove();
        });
    });

    // Input type "file" personnalisé
    $( '.input-file-container').each( function() {
        customInputFile( $( this ) );
    });

    // Message "fonctionnalité en cours de développement"
    // Lorsque l'on clique sur un lien de suppression
    $( '.coming-soon' ).on('click', function(e) {
        e.preventDefault();
        // Faire apparaitre la pop-up et ajouter le bouton de fermeture
        $( '.coming-soon-dialog' ).fadeIn(100).css({ 'max-width': '460'});

        var popWidth = $( '.coming-soon-dialog' ).width(); //Trouver la largeur
        var popHeight = $( '.coming-soon-dialog' ).height(); //Trouver la largeur
        var screenHeight = $(window).height();
        var screenWidth = $(window).width();

        // Récupération du margin, qui permettra de centrer la fenêtre
        var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();
        var popMargLeft = screenWidth / 2 - popWidth / 2;

        // Apply Margin to Popup
        $( '.coming-soon-dialog' ).css({
            'margin-top' : popMargTop,
            'margin-left' : popMargLeft
        });

        // Apparition du fond - .css({'filter' : 'alpha(opacity=50)'}) pour corriger les bogues d'anciennes versions de IE
        $('body').append('<div id="overlay" class="overlay"></div>');
        $('#overlay').css({'filter' : 'alpha(opacity=50)'}).fadeIn(100);

        $( window ).resize(function() {
            var popWidth = $( '.coming-soon-dialog' ).width();
            var popHeight = $( '.coming-soon-dialog' ).height();
            var screenHeight = $(window).height();
            var screenWidth = $(window).width();

            var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();
            var popMargLeft = screenWidth / 2 - popWidth / 2;

            // Apply Margin to Popup
            $( '.coming-soon-dialog' ).css({
                'margin-top' : popMargTop,
                'margin-left' : popMargLeft
            });
        });

        $( window ).scroll(function() {
            var popHeight = $( '.coming-soon-dialog' ).height();
            var screenHeight = $(window).height();

            var popMargTop = screenHeight / 2 - popHeight / 2 + $( 'body' ).scrollTop();

            //Apply Margin to Popup
            $( '.coming-soon-dialog' ).css({
                'margin-top' : popMargTop
            });
        });

        return false;
    });

    // Gestion des boutons
    $( 'body' ).on('click', '.coming-soon-dialog a.close, #overlay', function(e) {
        e.preventDefault();
        $('#overlay , .coming-soon-dialog').fadeOut(100, function() {
            $('#overlay').remove();
        });
    });

});

// Quand tout est chargé (DOM + images)
$(window).load(function() {
    // Positionnement des images
    resizePictureToContainer( 'resize-to-container', true );
    resizePictureToContainer( 'center-in-container', false );
});

// Quand on redimensionne la fenêtre
$(window).resize(function() {
    // Positionnement des images
    setTimeout(function(){
        resizePictureToContainer( 'resize-to-container', true );
        resizePictureToContainer( 'center-in-container', false );
    }, 200);
});

// Quand on scroll
$( window ).scroll(function() {
    // Positionnement des images
    resizePictureToContainer( 'resize-to-container', true );
    resizePictureToContainer( 'center-in-container', false );
});