<?php
// app/views/services/show.php
$title  = (string)($service['name'] ?? 'Service');
$active = 'performance';

$name     = (string)($service['name'] ?? 'service');
$category = (string)($service['category_name'] ?? 'performance');
$price    = !empty($service['price_from']) ? (float)$service['price_from'] : null;
$desc     = (string)($service['description'] ?? '');
?>

<section class="perf-hero perf-hero--mini" style="--perf-hero-img: url('<?= BASE_URL ?>/assets/img/performance-hero.jpg');">
  <div class="perf-hero__overlay"></div>

  <div class="perf-hero__inner">
    <div class="perf-hero__content">
      <p class="perf-hero__kicker"><?= htmlspecialchars($category) ?></p>
      <h1 class="perf-hero__title"><?= htmlspecialchars($name) ?></h1>

      <div class="perf-meta">
        <span class="perf-meta__item">
          <?= htmlspecialchars($category) ?>
        </span>

        <?php if ($price !== null): ?>
          <span class="perf-meta__sep">•</span>
          <span class="perf-meta__item">
            dès <?= number_format($price, 0, ',', ' ') ?> €
          </span>
        <?php endif; ?>
      </div>

      <div class="pagehead__actions perf-actions">
        <a class="btn btn--ghost" href="<?= BASE_URL ?>/services">retour services</a>
        <a class="btn" href="<?= BASE_URL ?>/contact">obtenir un devis</a>
      </div>
    </div>
  </div>
</section>

<section class="surface perf-surface perf-show">
  <?php if (!empty(trim($desc))): ?>
    <div class="perf-prose">
      <?= nl2br(htmlspecialchars($desc), false) ?>
    </div>
  <?php else: ?>
    <p class="muted" style="margin:0">Description à venir.</p>
  <?php endif; ?>
</section>
