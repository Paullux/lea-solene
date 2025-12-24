  <!-- PLAYER -->
  <footer id="player"
    class="fixed bottom-0 left-0 w-full bg-lea-panel/90 backdrop-blur-md border-t border-black/10">

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
  </footer>
  <div id="rgpd-banner" class="rgpd-hidden">
    <p>
      Ce site utilise des cookies pour mesurer l’audience et améliorer l’expérience.
      Vous pouvez accepter ou refuser.
    </p>
    <div class="rgpd-actions">
      <button id="rgpd-accept">Accepter</button>
      <button id="rgpd-refuse">Refuser</button>
    </div>
  </div>
</body>
</html>
