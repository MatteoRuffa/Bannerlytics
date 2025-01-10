jQuery(document).ready(function ($) {
    // Aggiorna il valore della larghezza durante lo scorrimento
    $('#container_width_slider').on('input', function () {
        let widthValue = parseInt($(this).val()) + ' px'; // Assicura che sia un numero in pixel
        $('#width_value').text(widthValue);
        $('.banner').css('max-width', $(this).val() + 'px'); // Imposta la larghezza effettiva in pixel
    });

    // Forza l'aggiornamento iniziale se necessario
    let initialValue = $('#container_width_slider').val();
    $('#width_value').text(initialValue + ' px');
    $('.banner').css('max-width', initialValue + 'px');
});