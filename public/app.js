// JS moderne pour animations et interactions fluides

document.addEventListener('DOMContentLoaded', () => {
    const cta = document.querySelector('.cta');
    if (cta) {
        cta.addEventListener('click', () => {
            document.getElementById('formations').scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Affichage de la fiche programme en modale (JS)
    document.querySelectorAll('.card-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.getAttribute('href') && this.getAttribute('href').includes('fiche=')) {
                // Lien normal, laisser faire
                return;
            }
            e.preventDefault();
        });
    });
});
