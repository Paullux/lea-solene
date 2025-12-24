<?php include 'includes/header.php'; ?>
<?php include 'data/tracks.php'; ?>
<?php $lyrics = require __DIR__ . '/data/lyrics.php'; ?>


<!-- INTRO + EP -->
<section class="mt-16">
  <!-- Titre -->
  <div class="max-w-4xl mx-auto flex items-center gap-6 my-8">
    <div class="flex-1 h-px bg-black/30"></div>
    <h3 class="font-elite text-2xl font-bold tracking-wide text-center whitespace-nowrap">
      Intro / EP
    </h3>
    <div class="flex-1 h-px bg-black/30"></div>
  </div>
  <!-- Contenu -->
  <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-start font-serif">
    <!-- Intro -->
    <div class="bg-lea-panel p-6 max-w-md">
      <p class="font-elite leading-relaxed">
        Moi, Léa Solène.<br>
        Je chante pour apaiser.<br>
        Pour celles et ceux qui avancent lentement,<br>
        avec leurs cicatrices et leur lumière.
      </p>
    </div>
    <!-- EP -->
    <div class="flex gap-6">
      <img
        src="assets/img/roots-and-light.jpg"
        alt="Roots & Light"
        class="w-40 h-40 object-cover"
      >
      <div>
        <p class="font-elite font-semibold mb-2">Maxi EP<br>Roots & Light</p>
        <ul class="space-y-2 text-sm">
          <?php foreach ($tracks as $track): ?>
            <li class="flex items-center gap-2">
              <span><?= htmlspecialchars($track['title']) ?></span>
              <button
                class="play-track"
                data-audio="<?= $track['audio'] ?>"
                data-title="<?= htmlspecialchars($track['title']) ?>"
                aria-label="Écouter"
              >
                ▶
              </button>
              <a href="<?= $track['youtube'] ?>"
                aria-label="Écouter <?= htmlspecialchars($track['title']) ?> sur YouTube">
                <img src="assets/img/YouTube.svg" alt="YouTube" class="w-4 h-4">
              </a>
              <a href="<?= $track['tiktok'] ?>"
                aria-label="Écouter <?= htmlspecialchars($track['title']) ?> sur TikTok">
                <img src="assets/img/tiktok.svg" alt="TikTok" class="w-4 h-4">
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- PAROLES -->
<section class="mt-24">
  <!-- Titre -->
  <div class="max-w-4xl mx-auto flex items-center gap-6 my-8">
    <div class="flex-1 h-px bg-black/30"></div>
    <h3 class="font-elite text-2xl font-bold tracking-wide text-center whitespace-nowrap">
      Paroles
    </h3>
    <div class="flex-1 h-px bg-black/30"></div>
  </div>
  <!-- Contenu -->
  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 items-start font-serif">
    <?php foreach ($lyrics as $track): ?>
      <article class="bg-lea-panel p-6 relative">
        <h3 class="font-elite font-semibold mb-2">
          <?= htmlspecialchars($track['title']) ?>
        </h3>
        <div class="lyrics collapsed text-sm leading-relaxed whitespace-pre-line">
          <?= htmlspecialchars($track['lyrics']) ?>
        </div>
        <div class="flex items-center gap-3 mt-3">
          <button
            class="play-track"
            data-audio="<?= $track['audio'] ?>"
            data-title="<?= htmlspecialchars($track['title']) ?>"
            aria-label="Écouter"
          >
            ▶
          </button>
          <a href="<?= $track['youtube'] ?>" target="_blank">
            <img src="assets/img/YouTube.svg" class="w-4 h-4">
          </a>
          <a href="<?= $track['tiktok'] ?>" target="_blank">
            <img src="assets/img/tiktok.svg" class="w-4 h-4">
          </a>
          <button class="toggle-lyrics ml-auto" aria-label="Afficher les paroles">⋯</button>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
