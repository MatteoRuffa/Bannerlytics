jQuery(document).ready(function($) {
    // Seleziono il campo URL e l'img preview
    let $imageField = $('#banner_image_url');
    let $previewImg = $('#banner_image_preview');

    // Funzione per aggiornare l'anteprima
    function updatePreview() {
        let url = $imageField.val().trim();
        if (url) {
            $previewImg.attr('src', url).show();
        } else {
            $previewImg.hide();
        }
    }

    // Evento: quando cambia il valore nel campo
    $imageField.on('input change', function() {
        updatePreview();
    });

    updatePreview();
});
