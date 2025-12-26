  <!-- Privacy and TOS -->
  <footer class="text-center py-8 border-t border-black/10 mt-12">
      
      <div class="flex gap-4 mb-4">
          </div>

      <p class="font-serif text-sm text-[#1E2D08]/60">
          &copy; <?= date('Y') ?> Léa Solène. Tous droits réservés.
      </p>
      
      <a href="legal.php" class="font-serif text-xs text-[#1E2D08]/40 hover:text-[#1E2D08] underline mt-2 transition-colors">
          Mentions légales & Confidentialité
      </a>

  </footer>

  <!-- PLAYER -->
<div id="audio-player"
    class="fixed bottom-0 left-0 w-full z-40 h-auto pb-4 md:pb-0 transform-none transition-transform duration-300
           text-[#1E2D08] 
           backdrop-blur-md
           bg-gradient-to-r from-[#435525]/60 via-[#B29442]/60 to-[#9A3B3B]/60
           border-t border-[#1E2D08]/55">

    <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-[#43591e] via-[#917300] to-[#672600] opacity-50"></div>

    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center gap-4">

      <!-- Play / Pause -->
      <button
        id="playBtn"
        class="w-10 h-10 rounded-full bg-black/10 hover:bg-black/20 flex items-center justify-center text-lg transition"
        aria-label="Lecture / Pause"
      >
        ▶
      </button>
      <button id="player-shuffle" title="Lecture aléatoire">🔀</button>
      <button id="player-repeat" title="Répétition">↻</button>
      <!-- Infos + progress -->
      <div class="flex-1 min-w-0">

        <!-- Titre -->
        <div
          id="player-title"
          class="font-elite text-sm font-semibold truncate mb-1"
        >
          —
        </div>

        <!-- Barre de progression -->
        <input
          id="player-progress"
          type="range"
          min="0"
          max="100"
          value="0"
          class="player-range w-full"
        >
      </div>

      <!-- Audio -->
      <audio id="audio"></audio>

      <div class="volume-control">
        <button
          id="player-volume"
          class="player-volume"
          aria-label="Volume"
        >
          🔊
        </button>

        <input
          id="volume-slider"
          type="range"
          min="0"
          max="100"
          value="80"
          class="volume-range"
          aria-label="Réglage du volume"
        >
      </div>
    </div>
  </div>
  <div id="rgpd-banner" class="rgpd-banner">
    <p>
      J'utilise Matomo pour savoir combien de personnes écoutent ma musique (anonymement). 
      Tu es d'accord ?
    </p>
    <div class="rgpd-actions">
      <button id="rgpd-accept">Accepter</button>
      <button id="rgpd-refuse">Refuser</button>
    </div>
  </div>
</body>
</html>
