document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM prêt');
    const buttons = document.querySelectorAll('.toggle-lyrics');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
        const article = button.closest('article');
        if (!article) return;

        const lyrics = article.querySelector('.lyrics');
        if (!lyrics) return;

        const isOpening = !lyrics.classList.contains('expanded');

        // referme tout le reste
        document.querySelectorAll('.lyrics.expanded').forEach((openLyrics) => {
            openLyrics.classList.remove('expanded');
            openLyrics.classList.add('collapsed');

            const openArticle = openLyrics.closest('article');
            const openBtn = openArticle?.querySelector('.toggle-lyrics');
            if (openBtn) openBtn.textContent = '⋯';
        });

        // ouvre/ferme celui cliqué
        if (isOpening) {
            lyrics.classList.add('expanded');
            lyrics.classList.remove('collapsed');
            button.textContent = '-';
        } else {
            lyrics.classList.remove('expanded');
            lyrics.classList.add('collapsed');
            button.textContent = '⋯';
        }
        
        // scroll seulement à l'ouverture
        if (isOpening) {
            setTimeout(() => {
            const rect = title.getBoundingClientRect();
            const absoluteTop = rect.top + window.pageYOffset;

            window.scrollTo({
                top: Math.max(absoluteTop - playerOffset, 0),
                behavior: 'smooth'
            });
            }, 300); // même durée que la transition CSS
        }
        });
    });
});
