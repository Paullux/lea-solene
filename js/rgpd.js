document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('rgpd-banner');
    const acceptBtn = document.getElementById('rgpd-accept');
    const refuseBtn = document.getElementById('rgpd-refuse');

    if (!banner || !acceptBtn || !refuseBtn) return;

    const consent = localStorage.getItem('rgpd-consent');

    if (!consent) {
        banner.classList.remove('rgpd-hidden');
    }

    acceptBtn.onclick = () => {
        localStorage.setItem('rgpd-consent', 'accepted');
        banner.remove();
        loadAnalytics();
    };

    refuseBtn.onclick = () => {
        localStorage.setItem('rgpd-consent', 'refused');
        banner.remove();
    };

    if (consent === 'accepted') {
        loadAnalytics();
    }
});

function loadAnalytics() {
    var _paq = window._paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="//stats.social-hub.fr/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '2']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
}
