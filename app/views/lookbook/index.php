<?php
// app/views/lookbook/index.php
$title  = 'Lookbook';
$active = 'lookbook';

/*
|--------------------------------------------------------------------------
| IMAGES LOOKBOOK
|--------------------------------------------------------------------------
*/
$coverMap = [
  1 => BASE_URL . '/assets/img/look1.jpg',
  2 => BASE_URL . '/assets/img/look4.jpg',
  3 => BASE_URL . '/assets/img/look2.jpg',
  4 => BASE_URL . '/assets/img/look3.jpg',
];

$fallbackCover = BASE_URL . '/assets/img/lookbook-thumb-fallback.jpg';
?>

<!-- HERO -->
<section class="lb-hero" style="--lb-hero-img: url('<?= BASE_URL ?>/assets/img/lookbook-hero.jpg');">
  <div class="lb-hero__overlay"></div>

  <div class="lb-hero__inner">
    <h1 class="lb-hero__title">
  Notre <span class="lb-neon">LookBook</span>
</h1>
  </div>
</section>

<section class="lb-wrap">

  <section class="pagehead lb-pagehead">
    <div>
      <h2>lookbook</h2>
      <p class="muted">
        Découvrez quelques réalisations : configurations, objectifs et résultats.
        Chaque projet est pensé sur mesure.
      </p>
    </div>

    <div class="pagehead__actions">
      <a class="btn" href="<?= BASE_URL ?>/contact">demander un devis</a>
      <a class="btn btn--ghost" href="<?= BASE_URL ?>/services">voir les services</a>
    </div>
  </section>

  <?php if (empty($projects)): ?>
    <section class="surface lb-empty">
      <p class="muted" style="margin:0">Aucune réalisation pour le moment.</p>
    </section>
  <?php else: ?>

    <section class="lb-list">
      <?php foreach ($projects as $p): ?>
        <?php
          $id       = (int)($p['id'] ?? 0);
          $title    = (string)($p['title'] ?? 'Projet');
          $subtitle = (string)($p['subtitle'] ?? '');
          $desc     = (string)($p['description'] ?? '');

          
          $coverUrl = $coverMap[$id] ?? $fallbackCover;
        ?>

        <article class="lb-item">

          <a
            class="lb-item__media"
            href="<?= BASE_URL ?>/lookbook/<?= $id ?>"
            style="background-image:url('<?= htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8') ?>');"
            aria-label="voir le projet <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
          ></a>

          <div class="lb-item__body">

            <p class="lb-item__cat">
              <?= htmlspecialchars($subtitle !== '' ? $subtitle : 'Préparation moteur', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <h2 class="lb-item__title">
              <a href="<?= BASE_URL ?>/lookbook/<?= $id ?>">
                <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
              </a>
            </h2>

            <?php if ($desc !== ''): ?>
              <p class="lb-item__desc">
                <?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?>
              </p>
            <?php endif; ?>

            <div class="lb-item__actions">
              <a class="btn btn--ghost btn--pill" href="<?= BASE_URL ?>/lookbook/<?= $id ?>">voir le projet</a>
              <a class="btn btn--ghost btn--pill" href="<?= BASE_URL ?>/contact">contacter</a>
            </div>

          </div>
        </article>

      <?php endforeach; ?>
    </section>

  <?php endif; ?>

</section>
