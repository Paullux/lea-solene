document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('newsletterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        const msg = document.getElementById('newsletter-message');
        const email = this.querySelector('input[name="email"]').value;
        
        btn.textContent = 'Connexion...';
        btn.disabled = true;

        // Envoi des données au script PHP via AJAX (Fetch)
        fetch('subscribe.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email)
        })
        .then(response => response.json())
        .then(data => {
            msg.style.display = 'block';
            if(data.success) {
                msg.textContent = 'Merci. Ta lumière est bien reçue. ✨';
                msg.className = 'font-serif text-base mt-4 text-[#1E2D08] font-bold animate-fade-in';
                document.getElementById('newsletterForm').reset();
            } else {
                msg.textContent = 'Une erreur est survenue : ' + data.message;
                msg.className = 'error';
            }
        })
        .catch(error => {
            msg.style.display = 'block';
            msg.textContent = 'Erreur de connexion.';
            msg.className = 'error';
        })
        .finally(() => {
            btn.textContent = "S'inscrire";
            btn.disabled = false;
        });
    });
});
