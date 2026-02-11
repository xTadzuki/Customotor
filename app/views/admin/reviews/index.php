<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Avis';
$active = 'admin_reviews';

$updated = (bool)($updated ?? (isset($_GET['updated']) && $_GET['updated'] === '1'));
$allowedStatus = ['pending', 'approved', 'rejected'];

$reviews = is_array($reviews ?? null) ? $reviews : [];

function clip(string $text, int $max = 120): string {
  $t = trim($text);
  if (mb_strlen($t) <= $max) return $t;
  return mb_substr($t, 0, $max) . '…';
}

$labelStatus = function (string $st): string {
  return match ($st) {
    'approved' => 'Approuvé',
    'rejected' => 'Rejeté',
    default    => 'En attente',
  };
};

$badgeClass = function (string $st): string {
  
  return match ($st) {
    'approved' => 'confirmed',
    'rejected' => 'cancelled',
    default    => 'pending',
  };
};
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Avis clients
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Modération des avis : validation, rejet, suivi.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin">Dashboard</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/logout">
        Déconnexion <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <?php if ($updated): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong>
        Statut mis à jour.
      </div>
    <?php endif; ?>

    <div class="cm-auth-card cm-admin-card reveal reveal--2">

      <div class="cm-admin-head">
        <h2 class="cm-admin-card__title">Liste des avis</h2>
        <p class="cm-admin-card__subtitle">Clique sur un avis pour le modérer en détail.</p>
      </div>

      <?php if (empty($reviews)): ?>
        <p class="cm-admin-empty">Aucun avis.</p>
      <?php else: ?>

        <div class="cm-admin-toolbar">
          <div class="cm-admin-toolbar__hint muted">
            <?= count($reviews) ?> avis
          </div>
        </div>

        <div class="cm-admin-table">
          <div style="overflow:auto">
            <table class="table">
              <thead>
                <tr>
                  <th>id</th>
                  <th>client</th>
                  <th>email</th>
                  <th>note</th>
                  <th>commentaire</th>
                  <th>statut</th>
                  <th>actions</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($reviews as $r): ?>
                  <?php
                    $id = (int)($r['id'] ?? 0);

                    $st = (string)($r['status'] ?? 'pending');
                    if (!in_array($st, $allowedStatus, true)) $st = 'pending';

                    $commentFull  = (string)($r['comment'] ?? '');
                    $commentShort = clip($commentFull, 140);

                    $clientName = trim((string)($r['firstname'] ?? '') . ' ' . (string)($r['lastname'] ?? ''));
                    $email = (string)($r['email'] ?? '');
                    $rating = (int)($r['rating'] ?? 0);

                    $statusClass = $badgeClass($st);
                    $statusText  = $labelStatus($st);
                  ?>
                  <tr>
                    <td>
                      <a class="cm-link-more" href="<?= BASE_URL ?>/admin/reviews/<?= $id ?>/edit">
                        #<?= $id ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                      </a>
                    </td>

                    <td><?= htmlspecialchars($clientName !== '' ? $clientName : '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="muted"><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                      <span class="cm-rating">
                        <?= $rating ?>/5
                      </span>
                    </td>

                    <td class="muted cm-review-cell" title="<?= htmlspecialchars($commentFull, ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($commentShort, ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                      <span class="status status--<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') ?>
                      </span>
                    </td>

                    <td class="cm-admin-actions">
                      <a class="cm-btn cm-btn--outline cm-btn--sm" href="<?= BASE_URL ?>/admin/reviews/<?= $id ?>/edit">
                        Éditer
                      </a>

                      <form class="cm-admin-inlineform" method="post" action="<?= BASE_URL ?>/admin/reviews/<?= $id ?>">
                        <?= csrf_field() ?>

                        <select class="select" name="status" required>
                          <?php foreach ($allowedStatus as $v): ?>
                            <option value="<?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?>" <?= $st === $v ? 'selected' : '' ?>>
                              <?= htmlspecialchars($labelStatus($v), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                          <?php endforeach; ?>
                        </select>

                        <button class="cm-btn cm-btn--outline cm-btn--sm" type="submit">OK</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      <?php endif; ?>

    </div>

  </div>
</section>
