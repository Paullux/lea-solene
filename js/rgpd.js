document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('rgpd-banner');
    const acceptBtn = document.getElementById('rgpd-accept');
    const refuseBtn = document.getElementById('rgpd-refuse');

    // --- 1. CONFIGURATION MATOMO (Pour tout le monde) ---
    var _paq = window._paq = window._paq || [];

    // Récupère le choix précédent
    const consent = localStorage.getItem('rgpd-consent');

    // --- 2. LE SECRET DE L'EXEMPTION ---
    // Si l'utilisateur n'a pas explicitement accepté, on désactive les cookies.
    // Cela permet de tracker les visites (TikTok, etc.) légalement sans attendre le clic.
    if (consent !== 'accepted') {
        _paq.push(['disableCookies']);
    }

    // --- 3. LANCEMENT DU TRACKING ---
    // On lance Matomo immédiatement, qu'il y ait consentement ou non.
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);

    (function() {
        var u="//stats.social-hub.fr/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '2']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();

    // --- 4. GESTION DE L'INTERFACE (Bannière) ---
    if (!banner || !acceptBtn || !refuseBtn) return;

    // Si un choix a déjà été fait (Accepté OU Refusé), on cache la bannière
    if (consent) {
        banner.classList.add('rgpd-hidden');
    }

    acceptBtn.onclick = () => {
        localStorage.setItem('rgpd-consent', 'accepted');
        banner.classList.add('rgpd-hidden');
        // Au prochain rechargement de page, la ligne 'disableCookies' ne sera plus exécutée.
        // Tu auras alors des stats précises (visiteurs récurrents, etc.).
    };

    refuseBtn.onclick = () => {
        localStorage.setItem('rgpd-consent', 'refused');
        banner.classList.add('rgpd-hidden');
        // On reste en mode "sans cookies" (le tracking continue de façon anonyme).
    };
});