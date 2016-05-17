function resizePictureToContainer( cssClass ){ 
 
    $( 'img.' + cssClass ).each( function() {

        var picture_width = $( this ).width(); 
        var picture_height = $( this ).height();     
         
        var over = picture_width / picture_height; 
        var under = picture_height / picture_width; 
         
        var container_width = $( this ).parent().width() + 2; 
        var container_height = $( this ).parent().height() + 2; 
        
        if (container_width / container_height >= over) { 
          $( this ).css({ 
            'width': container_width + 'px', 
            'height': Math.ceil(under * container_width) + 'px', 
            'left': '0px', 
            'top': Math.abs((under * container_width) - container_height) / -2 + 'px' 
          }); 
        }  
        else { 
          $( this ).css({ 
            'width': Math.ceil(over * container_height) + 'px', 
            'height': container_height + 'px', 
            'top': '0px', 
            'left': Math.abs((over * container_height) - container_width) / -2 + 'px' 
          }); 
        }

    }); 
} 

// Quand le DOM est chargé
$( document ).ready(function() {

    // Input type "file" personnalisé

    var fileInput  = $( '.input-file-container input[type="file"]' ),
        button     = $( '.input-file-container button' ),
        the_return = $( '.input-file-container input[type="text"]' );

    button.on( 'keydown', function( event ) {
        if ( event.keyCode == 13 || event.keyCode == 32 ) { 
            fileInput.click();
            return false; 
        }
    });

    button.on( 'click', function( event ) {
       fileInput.click();
       return false;  
    });

    fileInput.on( 'change', function( event ) {
        var file_name_pieces = $( this ).val().split('/');
        var file_name_pieces = $( this ).val().split('\\');
        var file_name = file_name_pieces[file_name_pieces.length - 1];
        the_return.val( file_name );
    });

});

// Quand tout est chargé (DOM + images)
$(window).load(function() {
    // Positionnement des images
    resizePictureToContainer( 'resize-to-container' );
});