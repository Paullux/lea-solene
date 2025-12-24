document.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('audio');
    const playBtn = document.getElementById('playBtn');
    const title = document.getElementById('player-title');
    const progress = document.getElementById('player-progress');
    let currentIndex = null;
    let shuffle = false;
    let repeatMode = 'off'; // 'off' | 'all' | 'one'

    const tracks = [
        {
            title: 'Sous La Lumière Douce',
            src: 'assets/audio/lea_solene_sous_la_lumiere_douce.mp3'
        },
        {
            title: "L'Écho de l'Espoir",
            src: 'assets/audio/lea_solene_l_echo_de_l_espoir.mp3'
        },
        {
            title: 'Ce Qui Reste',
            src: 'assets/audio/lea_solene_ce_qui_reste.wav'
        },
        {
            title: 'Je vais bien',
            src: 'assets/audio/lea_solene_je_vais_bien.mp3'
        }
    ];
    // clic sur un bouton MP3
    document.querySelectorAll('.play-track').forEach(button => {
        button.addEventListener('click', () => {
            const src = button.dataset.audio;
            const trackTitle = button.dataset.title;

            // si on clique sur un autre morceau
            if (audio.src !== location.origin + '/' + src) {
                audio.src = src;
                audio.play();

                title.textContent = trackTitle;
                playBtn.textContent = '⏸';

                // mettre à jour currentIndex si trouvé
                const index = tracks.findIndex(t => t.src === src);
                currentIndex = index !== -1 ? index : null;

                return;
            }

            // si on clique sur le même morceau
            if (audio.paused) {
                audio.play();
                playBtn.textContent = '⏸';
            } else {
                audio.pause();
                playBtn.textContent = '▶';
            }
        });
    });
    const shuffleBtn = document.getElementById('player-shuffle');
        shuffleBtn.addEventListener('click', () => {
        shuffle = !shuffle;
        shuffleBtn.classList.toggle('active', shuffle);
    });

    const repeatBtn = document.getElementById('player-repeat');

    repeatBtn.addEventListener('click', () => {
        if (repeatMode === 'off') {
            repeatMode = 'all';
            repeatBtn.textContent = '↻';
            repeatBtn.classList.add('active');
        } else if (repeatMode === 'all') {
            repeatMode = 'one';
            repeatBtn.textContent = '↺';
        } else {
            repeatMode = 'off';
            repeatBtn.textContent = '↻';
            repeatBtn.classList.remove('active');
        }
    });

    function playRandom() {
        let index;

        do {
            index = Math.floor(Math.random() * tracks.length);
        } while (index === currentIndex && tracks.length > 1);

        playTrack(index);
    }

    function playTrack(index) {
        currentIndex = index;
        audio.src = tracks[index].src;
        audio.play();

        document.getElementById('player-title').textContent = tracks[index].title;
        playBtn.textContent = '⏸';
    }

    // bouton volume
    const volumeBtn = document.getElementById('player-volume');
    const volumeSlider = document.getElementById('volume-slider');

    // volume initial
    audio.volume = volumeSlider.value / 100;
    audio.muted = false;

    // clic sur l'icône = mute
    volumeBtn.addEventListener('click', () => {
    audio.muted = !audio.muted;
    volumeBtn.textContent = audio.muted ? '🔇' : '🔊';
    });

    // slider = réglage fin
    volumeSlider.addEventListener('input', () => {
    audio.volume = volumeSlider.value / 100;

    if (audio.volume === 0) {
        audio.muted = true;
        volumeBtn.textContent = '🔇';
    } else {
        audio.muted = false;
        volumeBtn.textContent = '🔊';
    }
    });

    const savedVolume = localStorage.getItem('volume');
    if (savedVolume !== null) {
    volumeSlider.value = savedVolume;
    audio.volume = savedVolume / 100;
    }

    volumeSlider.addEventListener('input', () => {
    localStorage.setItem('volume', volumeSlider.value);
    });

    // bouton play / pause global
    playBtn.addEventListener('click', () => {
        if (!audio.src) {
            playRandom();
            return;
        }
        if (audio.paused) {
        audio.play();
        playBtn.textContent = '⏸';
        } else {
        audio.pause();
        playBtn.textContent = '▶';
        }
    });

    // progression
    audio.addEventListener('timeupdate', () => {
        if (!audio.duration) return;
        progress.value = (audio.currentTime / audio.duration) * 100;
    });

    // seek
    progress.addEventListener('input', () => {
        if (!audio.duration) return;
        audio.currentTime = (progress.value / 100) * audio.duration;
    });

    // fin de piste
    audio.addEventListener('ended', () => {
        if (repeatMode === 'one') {
            audio.currentTime = 0;
            audio.play();
            return;
        }

        if (shuffle) {
            playRandom();
            return;
        }

        if (repeatMode === 'all') {
            const next = (currentIndex + 1) % tracks.length;
            playTrack(next);
            return;
        }

        playBtn.textContent = '▶';
    });
});
