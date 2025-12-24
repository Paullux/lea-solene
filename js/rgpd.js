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
    // ici tu injectes ton script analytics
}
