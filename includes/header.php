<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta charset="utf-8">
  <title>Léa Solène - Roots & Light</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index, follow">

  <!-- Meta de base -->
  <meta name="description" content="Découvrez l'univers Roots Light de Léa Solène. Un projet musical indépendant entre reggae, douceur et lumière pour apaiser l'esprit.">
  <meta name="author" content="Léa Solène">

  <!-- OpenGraph / Facebook / Discord / LinkedIn -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="Léa Solène – Roots Light & Musique Solaire">
  <meta property="og:description" content="Un projet musical reggae indépendant et sensible. Des chansons pour celles et ceux qui avancent lentement, avec leurs cicatrices et leur lumière.">
  <meta property="og:url" content="https://lea-solene.fr/">
  <meta property="og:image" content="https://lea-solene.fr/assets/img/og-lea-solene.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="fr_FR">

  <!-- X / Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Léa Solène – Roots Light & Reggae Conscient">
  <meta name="twitter:description" content="Un projet musical indépendant mêlant reggae, douceur et poésie. À écouter, à regarder, à ressentir.">
  <meta name="twitter:image" content="https://lea-solene.fr/assets/img/og-lea-solene.jpg">

  <!-- Favicon -->
  <link rel="icon" href="/assets/img/favicon.png">

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">

  <!-- CSS perso -->
  <link rel="stylesheet" href="/css/main.css">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind config UNIQUE -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            lea: {
              bg: '#9a8f3b',
              panel: '#8a7f2f',
              text: '#1f1f1f'
            }
          },
          fontFamily: {
            elite: ['"Special Elite"', 'serif']
          }
        }
      }
    }
  </script>

  <!-- JS -->
  <script src="/js/lyrics.js" defer></script>
  <script src="/js/player.js" defer></script>
  <script src="/js/rgpd.js" defer></script>
  <script src="/js/newsletter.js" defer></script>
</head>

<body class="bg-lea-bg text-lea-text font-serif">

  <!-- HEADER -->
  <header class="max-w-5xl mx-auto pt-16">
    <img
      src="../assets/img/lea-header.jpg"
      alt="Léa Solène"
      class="w-full rounded-sm"
    >
  </header>
