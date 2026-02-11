<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Avis';
$active = 'admin_reviews';

if (empty($review)) {
  http_response_code(404);
  echo '404';
  exit;
}

$allowedStatus = ['pending','approved','rejected'];
$current = (string)($review['status'] ?? 'pending');
if (!in_array($current, $allowedStatus, true)) $current = 'pending';

$id = (int)($review['id'] ?? 0);

$client = trim((string)($review['firstname'] ?? '') . ' ' . (string)($review['lastname'] ?? ''));
$email  = (string)($review['email'] ?? '');
$rating = (int)($review['rating'] ?? 0);
$comment = (string)($review['comment'] ?? '');

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

$statusClass = $badgeClass($current);
$statusText  = $labelStatus($current);
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Avis #<?= $id ?>
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Modération et validation de l’avis.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/reviews">Retour</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/admin">
        Dashboard <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <div class="cm-admin-edit-grid">

      <!-- Contenu avis -->
      <div class="cm-auth-card cm-admin-card reveal reveal--2">
        <h2 class="cm-admin-card__title">Détails</h2>
        <p class="cm-admin-card__subtitle">Vérifie le contenu avant de valider.</p>

        <ul class="cm-admin-meta">
          <li>
            <span class="cm-admin-meta__label">Client</span>
            <span class="cm-admin-meta__value"><?= htmlspecialchars($client !== '' ? $client : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Email</span>
            <span class="cm-admin-meta__value muted"><?= htmlspecialchars($email !== '' ? $email : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Note</span>
            <span class="cm-admin-meta__value"><?= $rating ?>/5</span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Statut</span>
            <span class="status status--<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
              <?= htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') ?>
            </span>
          </li>
        </ul>

        <div class="cm-auth-divider"></div>

        <div class="cm-admin-reviewbox">
          <div class="cm-admin-reviewbox__label">Commentaire</div>
          <div class="cm-admin-reviewbox__text">
            <?= nl2br(htmlspecialchars($comment, ENT_QUOTES, 'UTF-8')) ?>
          </div>
        </div>
      </div>

      <!-- Modération -->
      <div class="cm-auth-card cm-admin-card reveal reveal--3">
        <h2 class="cm-admin-card__title">Modération</h2>
        <p class="cm-admin-card__subtitle">Choisis un statut, puis enregistre.</p>

        <form method="post" action="<?= BASE_URL ?>/admin/reviews/<?= $id ?>">
          <?= csrf_field() ?>

          <div class="cm-field">
            <label class="cm-label" for="status">Statut</label>
            <select class="select" id="status" name="status" required>
              <?php foreach ($allowedStatus as $st): ?>
                <option value="<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>" <?= $current === $st ? 'selected' : '' ?>>
                  <?= htmlspecialchars($labelStatus($st), ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="cm-auth-actions" style="margin-top:.75rem">
            <button class="cm-btn cm-btn--outline" type="submit">
              Enregistrer
            </button>
            <a class="cm-link-more" href="<?= BASE_URL ?>/admin/reviews">
              Retour liste <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </form>

        <div class="cm-auth-divider"></div>

        <p class="cm-auth-note">
          Astuce : “Approuvé” publie l’avis côté site, “Rejeté” le masque.
        </p>
      </div>

    </div>

  </div>
</section>
