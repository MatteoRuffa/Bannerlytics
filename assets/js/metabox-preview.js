jQuery(document).ready(function($) {
    var mediaUploader;

    // Pulsante "Seleziona immagine"
    $('.select-banner-image').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Inizializza la libreria media
        mediaUploader = wp.media({
            title: 'Seleziona un\'immagine',
            button: {
                text: 'Usa questa immagine'
            },
            multiple: false // Singola immagine
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#banner-image-url').val(attachment.url);
            $('#banner-image-preview').attr('src', attachment.url).show();
            $('.remove-banner-image').show();
        });

        mediaUploader.open();
    });

    // Pulsante "Rimuovi immagine"
    $('.remove-banner-image').on('click', function(e) {
        e.preventDefault();
        $('#banner-image-url').val('');
        $('#banner-image-preview').attr('src', '#').hide();
        $(this).hide();
    });
});
