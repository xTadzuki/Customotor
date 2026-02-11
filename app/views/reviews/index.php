

<section class="pagehead">
  <h1>avis clients</h1>
  <p class="muted">Retours publiés après modération.</p>

  <div class="pagehead__actions">
    <a class="btn btn--ghost" href="<?= BASE_URL ?>/services">voir les services</a>
<a class="btn" href="<?= BASE_URL ?>/contact">demander un devis</a>
  </div>
</section>

<?php if (empty($reviews)): ?>
  <section class="surface" style="padding:1.5rem">
    <p class="muted" style="margin:0">Aucun avis publié pour le moment.</p>
  </section>
<?php else: ?>
  <section class="reviewgrid">
    <?php foreach ($reviews as $r): ?>
      <?php
        $rating = (int)($r['rating'] ?? 0);
        if ($rating < 1) $rating = 1;
        if ($rating > 5) $rating = 5;

        $firstname = trim((string)($r['firstname'] ?? ''));
        $lastname  = trim((string)($r['lastname'] ?? ''));
        $initial   = $lastname !== '' ? mb_substr($lastname, 0, 1) . '.' : '';

        $displayName = trim($firstname . ' ' . $initial);

        $rawDate = (string)($r['created_at'] ?? '');
        $prettyDate = $rawDate;
        if ($rawDate !== '') {
          $dt = date_create($rawDate);
          if ($dt) {
            $prettyDate = $dt->format('d/m/Y');
          }
        }
      ?>

      <article class="reviewcard">
        <div class="reviewcard__head">
          <div class="stars" aria-label="note <?= $rating ?> sur 5">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span class="star <?= $i <= $rating ? 'star--on' : '' ?>" aria-hidden="true">★</span>
            <?php endfor; ?>
          </div>

          <p class="muted" style="margin:0">
            <?= htmlspecialchars($displayName !== '' ? $displayName : 'client') ?>
          </p>
        </div>

        <p class="muted" style="margin:.85rem 0 0; line-height:1.65; white-space:pre-wrap">
          <?= htmlspecialchars((string)($r['comment'] ?? '')) ?>
        </p>

        <p class="muted" style="margin:1rem 0 0; font-size:.9rem">
          <?= htmlspecialchars($prettyDate) ?>
        </p>
      </article>
    <?php endforeach; ?>
  </section>
<?php endif; ?>