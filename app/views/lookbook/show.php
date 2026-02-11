<?php
// app/views/lookbook/show.php

if (empty($project)) {
  http_response_code(404);
  echo '404';
  exit;
}

$title    = (string)($project['title'] ?? 'projet');
$subtitle = (string)($project['subtitle'] ?? '');
$desc     = (string)($project['description'] ?? '');
$images   = (array)($project['images'] ?? []);


$cover = (string)($project['cover'] ?? $project['hero'] ?? '');

$imgSrc = static function (string $path): string {
  $p = trim($path);
  if ($p === '') return '';
  if (preg_match('~^https?://~i', $p)) return $p;
  if (str_starts_with($p, '/')) return BASE_URL . $p;
  return BASE_URL . '/' . ltrim($p, '/');
};

// hero image
$heroImg = '';
if ($cover !== '') {
  $heroImg = $imgSrc($cover);
} elseif (!empty($images[0]['image_path'])) {
  $heroImg = $imgSrc((string)$images[0]['image_path']);
} else {
  $heroImg = BASE_URL . '/assets/img/lookbook-hero.jpg';
}

// galerie
$gallery = [];
foreach ($images as $img) {
  $src = $imgSrc((string)($img['image_path'] ?? ''));
  if ($src === '') continue;
  $alt = (string)($img['alt_text'] ?? $title);
  $gallery[] = ['src' => $src, 'alt' => $alt];
}
?>

<!-- HERO (show) -->
<section class="lb-hero lb-hero--show js-lb-hero"
  style="--lb-hero-img: url('<?= htmlspecialchars($heroImg, ENT_QUOTES, 'UTF-8') ?>');">
  <div class="lb-hero__overlay"></div>

  <div class="lb-hero__inner lb-hero__inner--show">
    <p class="lb-hero__kicker">
      <?= htmlspecialchars($subtitle !== '' ? $subtitle : 'Préparation moteur', ENT_QUOTES, 'UTF-8') ?>
    </p>

    <h1 class="lb-hero__title">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h1>

    <div class="lb-hero__actions">
      <a class="btn btn--ghost btn--pill" href="<?= BASE_URL ?>/lookbook">retour lookbook</a>
      <a class="btn btn--pill" href="<?= BASE_URL ?>/contact">demander un devis</a>
    </div>
  </div>
</section>

<section class="lb-wrap">

  <?php if ($desc !== ''): ?>
    <section class="surface lb-desc">
      <p class="muted lb-desc__text" style="margin:0; line-height:1.75; white-space:pre-wrap;">
        <?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?>
      </p>
    </section>
  <?php endif; ?>

  <?php if (!empty($gallery)): ?>
    <section class="lb-gallery" aria-label="Galerie du projet">
      <?php foreach ($gallery as $i => $g): ?>
        <figure class="lb-gitem">
          <a
            href="<?= htmlspecialchars($g['src'], ENT_QUOTES, 'UTF-8') ?>"
            class="lb-glink"
            data-lightbox="lb"
            data-index="<?= (int)$i ?>"
            aria-label="ouvrir l'image <?= (int)($i + 1) ?>"
          >
            <img
              src="<?= htmlspecialchars($g['src'], ENT_QUOTES, 'UTF-8') ?>"
              alt="<?= htmlspecialchars($g['alt'], ENT_QUOTES, 'UTF-8') ?>"
              loading="lazy"
            >
          </a>
        </figure>
      <?php endforeach; ?>
    </section>

    <!-- Lightbox -->
    <div id="lb" class="lb" hidden aria-hidden="true" role="dialog" aria-label="Agrandissement image">
      <button class="lb__close" type="button" aria-label="fermer">×</button>

      <button class="lb__nav lb__prev" type="button" aria-label="image précédente">‹</button>
      <img class="lb__img" alt="">
      <button class="lb__nav lb__next" type="button" aria-label="image suivante">›</button>
    </div>

    <script>
    (() => {
      "use strict";

      // HERO: arrivée comme Home/Performance
      const hero = document.querySelector(".js-lb-hero");
      if (hero) requestAnimationFrame(() => hero.classList.add("is-visible"));

      // LIGHTBOX
      const lb = document.getElementById("lb");
      if (!lb) return;

      const imgEl    = lb.querySelector(".lb__img");
      const closeBtn = lb.querySelector(".lb__close");
      const prevBtn  = lb.querySelector(".lb__prev");
      const nextBtn  = lb.querySelector(".lb__next");

      const links = Array.from(document.querySelectorAll('a[data-lightbox="lb"]'));
      if (!links.length) return;

      let index = 0;
      let lastFocus = null;
      let scrollY = 0;

      const setScrollLock = (locked) => {
        if (locked) {
          scrollY = window.scrollY || 0;
          document.body.style.position = "fixed";
          document.body.style.top = `-${scrollY}px`;
          document.body.style.left = "0";
          document.body.style.right = "0";
          document.body.style.width = "100%";
        } else {
          document.body.style.position = "";
          document.body.style.top = "";
          document.body.style.left = "";
          document.body.style.right = "";
          document.body.style.width = "";
          window.scrollTo(0, scrollY);
        }
      };

      const preload = (src) => { const i = new Image(); i.src = src; };

      const showAt = (i) => {
        index = (i + links.length) % links.length;
        const a = links[index];
        const im = a.querySelector("img");
        const src = a.getAttribute("href");
        const alt = im ? im.getAttribute("alt") : "";

        imgEl.src = src;
        imgEl.alt = alt || "";

        // précharge voisins
        const nextA = links[(index + 1) % links.length];
        const prevA = links[(index - 1 + links.length) % links.length];
        preload(nextA.getAttribute("href"));
        preload(prevA.getAttribute("href"));
      };

      const open = (i, openerEl) => {
        lastFocus = openerEl || document.activeElement;
        lb.hidden = false;
        lb.setAttribute("aria-hidden", "false");
        setScrollLock(true);
        showAt(i);
        closeBtn?.focus();
      };

      const close = () => {
        lb.hidden = true;
        lb.setAttribute("aria-hidden", "true");
        imgEl.src = "";
        setScrollLock(false);
        if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
      };

      document.addEventListener("click", (e) => {
        const a = e.target.closest('a[data-lightbox="lb"]');
        if (!a) return;
        e.preventDefault();
        const i = parseInt(a.getAttribute("data-index") || "0", 10);
        open(i, a);
      });

      closeBtn?.addEventListener("click", close);
      lb.addEventListener("click", (e) => { if (e.target === lb) close(); });

      prevBtn?.addEventListener("click", () => showAt(index - 1));
      nextBtn?.addEventListener("click", () => showAt(index + 1));

      document.addEventListener("keydown", (e) => {
        if (lb.hidden) return;
        if (e.key === "Escape") close();
        if (e.key === "ArrowLeft") showAt(index - 1);
        if (e.key === "ArrowRight") showAt(index + 1);
      });
    })();
    </script>

  <?php else: ?>
    <section class="surface lb-empty">
      <p class="muted" style="margin:0">Aucune image pour ce projet.</p>
    </section>
  <?php endif; ?>

</section>
