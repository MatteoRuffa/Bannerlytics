jQuery(document).ready(function($) {
    let mediaUploader;

    // Pulsante "Seleziona immagine"
    $(document).on('click', '.select-banner-image', function(e) {
        e.preventDefault();

        // Inizializza la libreria media di WordPress solo una volta
        if (!mediaUploader) {
            mediaUploader = wp.media({
                title: 'Seleziona un\'immagine',
                button: {
                    text: 'Usa questa immagine'
                },
                multiple: false // Permetti solo una immagine alla volta
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#banner-image-url').val(attachment.url);

                // Aggiunta anteprima immagine selezionata
                if ($('#banner-image-preview').length === 0) {
                    $('<img>', {
                        id: 'banner-image-preview',
                        class: 'banner-preview-image',
                        src: attachment.url,
                        alt: 'Anteprima immagine',
                        style: 'max-width: 100%; margin-top: 10px; display: block;'
                    }).insertAfter('.select-banner-image');
                } else {
                    $('#banner-image-preview').attr('src', attachment.url).show();
                }

                if ($('.remove-banner-image').length === 0) {
                    $('<button>', {
                        class: 'button remove-banner-image',
                        text: 'Rimuovi immagine',
                        style: 'margin-top: 10px; display: inline-block;'
                    }).insertAfter('#banner-image-preview');
                } else {
                    $('.remove-banner-image').show();
                }
            });
        }

        mediaUploader.open();
    });

    // Pulsante "Rimuovi immagine"
    $(document).on('click', '.remove-banner-image', function(e) {
        e.preventDefault();
        $('#banner-image-url').val('');
        $('#banner-image-preview').attr('src', '').hide();
        $(this).hide();
    });
});