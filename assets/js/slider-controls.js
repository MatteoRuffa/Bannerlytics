jQuery(document).ready(function ($) {
    // Aggiorna il valore della larghezza durante lo scorrimento
    $('#container_width_slider').on('input', function () {
        let widthValue = $(this).val() + '%';
        $('#width_value').text(widthValue); 
        $('.banner').css('max-width', widthValue); 
    });

    // Aggiorna il valore dell'altezza durante lo scorrimento
    $('#container_height_slider').on('input', function () {
        let heightValue = $(this).val() + 'px';
        $('#height_value').text(heightValue); 
        $('.banner').css('height', heightValue); 
    });
});
