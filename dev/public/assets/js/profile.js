$( document ).ready(function() {

    // On calcule l'affichage du graphique
    displayChart();

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

    // Quand on redimensionne la fenêtre
    $(window).resize(function() {
        // On recalcule l'affichage du graphique
        displayChart();
    });

});