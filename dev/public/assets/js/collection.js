/**
 * Met à jour le nombre des figurines contenues dans un set
 * après l'édition/suppression du-dit set
 * @param array minifigures_list Liste des figurines et nombre correspondant
 */
function updateMinifiguresCount(minifigures_list)
{
    $.each(minifigures_list, function(minifigure_id, value) {
        var thisItem = $( '[data-id="' + minifigure_id + '"]');
        var thisItemCol = thisItem.parents( '.item-col' );
        var thisItemRangeContainer = thisItem.parents( '.range-container' );
        var thisItemSerieContainer = thisItem.parents( '.serie-container' );
        // Si le nombre de figurine est supérieur à zéro
        if(value > 0) {
            // On met simplement à jour le champ
            thisItem.find( '.item-count').text('x' + value);
        }
        // Sinon
        else {
            // On supprime ...
            // ... toute la gamme si elle ne contient plus aucun élément
            if(thisItemRangeContainer.find( '.item-col').length <= 1) {
                thisItemRangeContainer.remove();
            }
            else {
                // ... toute la série si elle ne contient plus aucun élément
                if(thisItemSerieContainer.find( '.item-col').length <= 1) {
                    thisItemSerieContainer.remove();
                }
                // ou simplement la figurine
                else {
                    thisItemCol.remove();
                }
            }
        }
    });
}

$( document ).ready(function() {

    // On calcule l'affichage du graphique
    displayChart();

    /* SUPPRESSION D'UNE ANNONCE
     ------------------------------------------------------ */

    // Initialisation
    var theHREF; // Contient la cible du lien qui ouvre la pop-up

    // Lorsque l'on clique sur un lien de suppression
    $( 'a.delete-link' ).on('click', function(e) {

        e.preventDefault();

        theHREF = $(this).attr( 'href' );

        // Mise à jour du contenu de la pop-up
        $( '.delete-dialog td.title' ).text($(this).parent().siblings('.title').find('.title-text').text());
        $( '.delete-dialog td.type' ).text($(this).parent().siblings('.title').find('.type-text').text());

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

    /* SWITCH MINIFIGURES/SETS
     ------------------------------------------------------ */

    // Au clic sur un des boutons du switch
    $('.item-type-button').on('click', function() {
        // On active ce bouton
        $('.item-type-button').removeClass('active');
        $( this ).addClass('active');
        // On récupère le type d'item correspondant à ce bouton
        var itemType = $( this ).data('type');
        // On affiche la liste correspondante
        $('.item-list').removeClass('shown');
        $('.' + itemType + '-list').addClass('shown');
    });

    /* EDITION D'UN ITEM
     ------------------------------------------------------ */

    // Ouverture du panneau "nombre d'exemplaires"
    $( '.item .edit-button' ).click(function() {
        $( '.item-number' ).removeClass( 'active' );
        $( this ).parents( '.item-container' ).find( '.item-number' ).addClass( 'active' );
    });
    // fermeture du panneau "nombre d'exemplaires"
    $( document ).click( function(e) {
        if( !$(e.target).closest( '.item-number.active, .item .edit-button' ).length) {
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
    // Modification de la collection
    $( '.item-number .edit-button' ).click(function(e) {
        e.preventDefault();
        var itemId = $(this).siblings( '.input-container').find( '.item-id-input' ).val();
        var itemType = $(this).siblings( '.input-container').find( '.item-type-input' ).val();
        var countId = $(this).siblings( '.input-container' ).find( '.item-number-input' ).val();
        var numberPanel = $(this).parents( '.item-container' ).find( '.item-number-content' );
        var confirmationPanel = $(this).parents( '.item-container' ).find( '.item-number .item-confirmation-content' );
        var countTxt = $(this).parents( '.item-container' ).find( '.item-count' );
        $.ajax({
            url: '/collection/edit',
            data: 'item_id=' + itemId + '&item_count=' + countId + '&item_type=' + itemType,
            dataType: 'json',
            success: function(json) {
                // Si l'utilisateur n'est pas connecté
                if(json === false) {
                    // On le redirige vers la page de login
                    window.location.href = '/members/login';
                }
                // Si l'utilisateur est connecté et qu'on a bien modifié l'élément de la collection
                else {
                    // On affiche le panneau de confirmation...
                    numberPanel.addClass( 'hidden' );
                    confirmationPanel.removeClass( 'hidden' );
                    // On met à jour le nombre de figurine/set
                    countTxt.text('x' + countId);
                    // On met à jour le nombre de figurines contenues dans le set (si on a édité un set)
                    updateMinifiguresCount(json);
                }
            }
        });
    });

    /* SUPPRESSION D'UN ITEM
     ------------------------------------------------------ */

    // Ouverture du panneau "suppression"
    $( '.item .delete-button' ).click(function() {
        $( '.item-delete' ).removeClass( 'active' );
        $( this ).parents( '.item-container' ).find( '.item-delete' ).addClass( 'active' );
    });
    // fermeture du panneau "suppression"
    $( document ).click( function(e) {
        if( !$(e.target).closest( '.item-delete.active, .item .delete-button' ).length ||
            $(e.target).closest( '.item-delete.active .cancel-button' ).length ) {
            $( '.item-delete' ).removeClass( 'active' );
            $( '.item-delete-content' ).delay(animationDuration).removeClass( 'hidden' );
            $( '.item-delete .item-confirmation-content' ).delay(animationDuration).addClass( 'hidden' );
        }
    });
    // Modification de la collection
    $( '.item-delete .delete-button' ).click(function(e) {
        e.preventDefault();
        var itemId = $(this).siblings( '.item-id-input' ).val();
        var itemType = $(this).siblings( '.item-type-input' ).val();
        var deletePanel = $(this).parents( '.item-container' ).find( '.item-delete-content' );
        var confirmationPanel = $(this).parents( '.item-container' ).find( '.item-delete .item-confirmation-content' );
        var itemCol = $(this).parents( '.item-col' );
        var itemRangeContainer = $(this).parents( '.range-container' );
        var itemSerieContainer = $(this).parents( '.serie-container' );

        $.ajax({
            url: '/collection/delete',
            data: 'item_id=' + itemId + '&item_type=' + itemType,
            dataType: 'json',
            success: function(json) {
                // Si l'utilisateur n'est pas connecté
                if(json === false) {
                    // On le redirige vers la page de login
                    window.location.href = '/members/login';
                }
                // Si l'utilisateur est connecté et qu'on a bien supprimé l'élément de la collection
                else {
                    // On affiche le panneau de confirmation
                    deletePanel.addClass( 'hidden' );
                    confirmationPanel.removeClass( 'hidden' );
                    // On met à jour le nombre de figurines contenues dans le set (si on a édité un set)
                    updateMinifiguresCount(json);
                    // Puis on fait disparaitre ...
                    itemCol.delay( 1500 ).fadeOut( 1000, function() {
                        // ... toute la gamme si elle ne contient plus aucun élément
                        if(itemRangeContainer.find( '.item-col').length <= 1) {
                            itemRangeContainer.remove();
                        }
                        else {
                            // ... toute la série si elle ne contient plus aucun élément
                            if(itemSerieContainer.find( '.item-col').length <= 1) {
                                itemSerieContainer.remove();
                            }
                            // ou simplement la figurine
                            else {
                                itemCol.remove();
                            }
                        }
                    });
                }
            }
        });
    });

    // Quand on redimensionne la fenêtre
    $(window).resize(function() {
        // On recalcule l'affichage du graphique
        displayChart();
    });

});