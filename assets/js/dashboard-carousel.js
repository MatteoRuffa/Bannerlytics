document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carousel-track');  
    const items = document.querySelectorAll('.carousel-item');  
    const prevButton = document.querySelector('.carousel-prev');  
    const nextButton = document.querySelector('.carousel-next');  

    let currentIndex = 0;  // Indice corrente

    // Funzione per aggiornare la posizione di scorrimento
    function updateScrollPosition() {
        const cardHeight = items[0].offsetHeight + 20;  
        track.style.transform = `translateY(-${cardHeight * currentIndex}px)`;
    }

    // Evento per bottone "precedente"
    prevButton.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateScrollPosition();
        }
    });

    // Evento per bottone "successivo"
    nextButton.addEventListener('click', function () {
        if (currentIndex < items.length - 1) {
            currentIndex++;
            updateScrollPosition();
        }
    });
});
