document.addEventListener('DOMContentLoaded', () => {
    console.log("💿 Initialisation du Player (Mode Resume-On-Interaction)...");

    const audio = document.getElementById('audio');
    const playBtn = document.getElementById('playBtn');
    const title = document.getElementById('player-title');
    const progress = document.getElementById('player-progress');
    const volumeBtn = document.getElementById('player-volume');
    const volumeSlider = document.getElementById('volume-slider');

    if (!audio) return;

    audio.preload = "auto";

    const tracks = [
        { title: 'Sous La Lumière Douce', src: 'assets/audio/lea_solene_sous_la_lumiere_douce.mp3' },
        { title: "L'Écho de l'Espoir", src: 'assets/audio/lea_solene_l_echo_de_l_espoir.mp3' },
        { title: 'Ce Qui Reste', src: 'assets/audio/lea_solene_ce_qui_reste.wav' },
        { title: 'Je vais bien', src: 'assets/audio/lea_solene_je_vais_bien.mp3' },
        { title: 'Sous l’oeil de Jah', src: 'assets/audio/lea_solene_sous_l_oeil_de_jah.mp3' },
        { title: 'L\'Appel de Zion', src: 'assets/audio/lea_solene_appel_de_zion.mp3' }
    ];

    let currentIndex = 0;
    let shuffle = false;
    let repeatMode = 'off';
    let isRestoring = false;

    // ======================================================
    // 1. RESTAURATION INTELLIGENTE
    // ======================================================
    const initPlayer = async () => {
        try {
            const savedState = JSON.parse(sessionStorage.getItem('leaPlayerState'));

            if (savedState) {
                console.log("💾 Sauvegarde trouvée :", savedState);
                isRestoring = true; 

                currentIndex = savedState.trackIndex || 0;
                const targetTime = savedState.currentTime || 0;
                const savedVolume = (savedState.volume !== undefined) ? savedState.volume : 1;
                const wasPlaying = savedState.isPlaying;

                // 1. Charger la source
                loadTrack(currentIndex);
                
                // 2. Appliquer le volume
                audio.volume = savedVolume;
                if(volumeSlider) volumeSlider.value = savedVolume * 100;

                // -----------------------------------------------------------
                // 3. MISE A JOUR VISUELLE (C'est la partie que tu as demandée)
                // -----------------------------------------------------------
                const updateVisuals = () => {
                    if (progress && audio.duration) {
                        // On calcule le pourcentage et on l'applique
                        const pct = (targetTime / audio.duration) * 100;
                        progress.value = pct;
                        
                        // Optionnel : Si tu affichais le temps textuel (ex: 1:24), c'est ici qu'il faudrait le mettre à jour
                    }
                };

                // Si les métadonnées (durée) sont déjà là, on met à jour tout de suite
                if (audio.readyState >= 1) {
                    updateVisuals();
                } else {
                    // Sinon on attend qu'elles arrivent
                    audio.addEventListener('loadedmetadata', updateVisuals, { once: true });
                }
                // -----------------------------------------------------------

                // 4. LA TENTATIVE DE LECTURE (Strategie Interaction)
                const attemptResume = async () => {
                    if (!wasPlaying) {
                        audio.currentTime = targetTime;
                        isRestoring = false;
                        return; // On était en pause, on s'arrête là.
                    }

                    try {
                        // On mute pour le confort
                        audio.muted = true; 
                        
                        await audio.play();
                        
                        // SUCCÈS : Le navigateur a autorisé l'autoplay
                        console.log(`🚀 Autoplay OK. Seek vers ${targetTime}s`);
                        audio.currentTime = targetTime;
                        audio.muted = false;
                        playBtn.textContent = '⏸';
                        isRestoring = false;

                    } catch (error) {
                        // ÉCHEC : Le navigateur a bloqué
                        console.warn("⚠️ Autoplay bloqué. En attente d'interaction.");
                        playBtn.textContent = '▶';
                        
                        // On cale quand même le temps (Best effort)
                        audio.currentTime = targetTime;
                        audio.muted = false;

                        // PIÈGE À CLIC : Le prochain clic (n'importe où) lancera la musique
                        const resumeOnInteraction = async () => {
                            try {
                                await audio.play();
                                audio.currentTime = targetTime; // On re-force le temps pour être sûr
                                playBtn.textContent = '⏸';
                                console.log("✅ Reprise au clic.");
                            } catch (e) {
                                console.error(e);
                            } finally {
                                // Nettoyage des écouteurs
                                document.removeEventListener('click', resumeOnInteraction);
                                document.removeEventListener('keydown', resumeOnInteraction);
                                document.removeEventListener('touchstart', resumeOnInteraction);
                                isRestoring = false;
                            }
                        };

                        document.addEventListener('click', resumeOnInteraction, { once: true });
                        document.addEventListener('keydown', resumeOnInteraction, { once: true });
                        document.addEventListener('touchstart', resumeOnInteraction, { once: true });
                    }
                };

                // On lance la tentative
                if (audio.readyState >= 1) {
                    attemptResume();
                } else {
                    audio.addEventListener('loadedmetadata', attemptResume, { once: true });
                }

            } else {
                // Pas de sauvegarde
                loadTrack(0);
                const localVol = localStorage.getItem('volume');
                if (localVol && volumeSlider) {
                    audio.volume = localVol / 100;
                    volumeSlider.value = localVol;
                }
            }
        } catch (e) {
            console.error(e);
            isRestoring = false;
            audio.muted = false;
        }
    };

    // ======================================================
    // 2. FONCTIONS STANDARD
    // ======================================================
    function loadTrack(index) {
        currentIndex = index;
        const newSrc = tracks[index].src;
        if (!audio.src.includes(newSrc)) {
            audio.src = newSrc;
            title.textContent = tracks[index].title;
        }
    }

    function playTrack(index) {
        loadTrack(index);
        audio.muted = false;
        // Simple play sans gestion complexe (l'interaction utilisateur est là)
        audio.play().then(() => playBtn.textContent = '⏸').catch(console.error);
    }

    function togglePlay() {
        audio.muted = false;
        if (audio.paused) {
            if (!audio.src) loadTrack(0);
            audio.play().then(() => playBtn.textContent = '⏸');
        } else {
            audio.pause();
            playBtn.textContent = '▶';
        }
    }

    // ======================================================
    // 3. SAUVEGARDE
    // ======================================================
    audio.addEventListener('timeupdate', () => {
        if (progress && audio.duration) {
            progress.value = (audio.currentTime / audio.duration) * 100;
        }

        if (isRestoring || audio.currentTime < 0.2) return; 

        const state = {
            trackIndex: currentIndex,
            currentTime: audio.currentTime,
            isPlaying: !audio.paused,
            volume: audio.volume
        };
        sessionStorage.setItem('leaPlayerState', JSON.stringify(state));
    });

    // ======================================================
    // 4. UI EVENTS & CLAVIER
    // ======================================================
    if(playBtn) playBtn.addEventListener('click', () => { isRestoring = false; togglePlay(); });

    document.querySelectorAll('.play-track').forEach(button => {
        button.addEventListener('click', () => {
            isRestoring = false;
            const src = button.dataset.audio;
            const index = tracks.findIndex(t => src.includes(t.src));
            if (index !== -1) {
                if (currentIndex === index && !audio.paused) togglePlay();
                else playTrack(index);
            }
        });
    });

    if(progress) {
        progress.addEventListener('input', () => {
            isRestoring = false;
            if (audio.duration) audio.currentTime = (progress.value / 100) * audio.duration;
        });
    }

    if(volumeBtn) {
        volumeBtn.addEventListener('click', () => {
            audio.muted = !audio.muted;
            volumeBtn.textContent = audio.muted ? '🔇' : '🔊';
        });
    }
    if(volumeSlider) {
        volumeSlider.addEventListener('input', () => {
            audio.volume = volumeSlider.value / 100;
            audio.muted = (audio.volume === 0);
            if(volumeBtn) volumeBtn.textContent = audio.muted ? '🔇' : '🔊';
            localStorage.setItem('volume', volumeSlider.value);
        });
    }

    const shuffleBtn = document.getElementById('player-shuffle');
    if(shuffleBtn) {
        shuffleBtn.addEventListener('click', () => {
            shuffle = !shuffle;
            shuffleBtn.classList.toggle('active', shuffle);
        });
    }
    const repeatBtn = document.getElementById('player-repeat');
    if(repeatBtn) {
        repeatBtn.addEventListener('click', () => {
            if (repeatMode === 'off') { repeatMode = 'all'; repeatBtn.textContent = '↻'; repeatBtn.classList.add('active'); }
            else if (repeatMode === 'all') { repeatMode = 'one'; repeatBtn.textContent = '↺'; }
            else { repeatMode = 'off'; repeatBtn.textContent = '↻'; repeatBtn.classList.remove('active'); }
        });
    }

    audio.addEventListener('ended', () => {
        if (repeatMode === 'one') { audio.currentTime = 0; audio.play(); }
        else if (shuffle) {
            let next;
            do { next = Math.floor(Math.random() * tracks.length); } while (next === currentIndex && tracks.length > 1);
            playTrack(next);
        } else if (repeatMode === 'all') {
            playTrack((currentIndex + 1) % tracks.length);
        } else {
            playBtn.textContent = '▶';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        isRestoring = false;
        switch(e.key) {
            case 'ArrowLeft': 
                e.preventDefault(); 
                audio.currentTime = Math.max(0, audio.currentTime - 10); 
                break;
            case 'ArrowRight': 
                e.preventDefault();
                audio.currentTime = Math.min(audio.duration, audio.currentTime + 10); 
                break;
            case ' ': 
            case 'Spacebar': 
                e.preventDefault(); 
                togglePlay();
                break;
        }
    });

    initPlayer();
});