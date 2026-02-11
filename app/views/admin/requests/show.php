<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Demande';
$active = 'admin_requests';

if (empty($request)) {
  http_response_code(404);
  echo '404';
  exit;
}

$updated = (bool)($updated ?? (isset($_GET['updated']) && $_GET['updated'] === '1'));

$allowedStatus = ['new', 'in_progress', 'done'];
$current = (string)($request['status'] ?? 'new');
if (!in_array($current, $allowedStatus, true)) $current = 'new';

$id = (int)($request['id'] ?? 0);

$clientName = trim((string)($request['firstname'] ?? '') . ' ' . (string)($request['lastname'] ?? ''));
$email = (string)($request['email'] ?? '');
$phone = (string)($request['phone'] ?? '');

$vehicle = trim((string)($request['brand'] ?? '') . ' ' . (string)($request['model'] ?? ''));
$year = $request['year'] ?? null;

$message = (string)($request['message'] ?? '');
$createdAt = (string)($request['created_at'] ?? '');

$labelStatus = function (string $st): string {
  return match ($st) {
    'in_progress' => 'En cours',
    'done'        => 'Terminé',
    default       => 'Nouveau',
  };
};

$badgeClass = function (string $st): string {
  return match ($st) {
    'in_progress' => 'confirmed',
    'done'        => 'cancelled',
    default       => 'pending',
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
      Demande #<?= $id ?>
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Détail de la demande + changement de statut.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/requests">Retour liste</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/admin">
        Dashboard <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
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

    <div class="cm-admin-edit-grid">

      <!-- Détails -->
      <div class="cm-auth-card cm-admin-card reveal reveal--2">
        <h2 class="cm-admin-card__title">Détails</h2>
        <p class="cm-admin-card__subtitle">Infos client & véhicule.</p>

        <ul class="cm-admin-meta">
          <li>
            <span class="cm-admin-meta__label">Client</span>
            <span class="cm-admin-meta__value"><?= htmlspecialchars($clientName !== '' ? $clientName : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>

          <li>
            <span class="cm-admin-meta__label">Email</span>
            <span class="cm-admin-meta__value muted"><?= htmlspecialchars($email !== '' ? $email : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>

          <li>
            <span class="cm-admin-meta__label">Téléphone</span>
            <span class="cm-admin-meta__value muted"><?= htmlspecialchars($phone !== '' ? $phone : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>

          <li>
            <span class="cm-admin-meta__label">Véhicule</span>
            <span class="cm-admin-meta__value">
              <?= htmlspecialchars($vehicle !== '' ? $vehicle : '—', ENT_QUOTES, 'UTF-8') ?>
              <?php if (!empty($year)): ?>
                <span class="muted">(<?= (int)$year ?>)</span>
              <?php endif; ?>
            </span>
          </li>

          <li>
            <span class="cm-admin-meta__label">Statut</span>
            <span class="status status--<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
              <?= htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') ?>
            </span>
          </li>

          <li>
            <span class="cm-admin-meta__label">Reçue le</span>
            <span class="cm-admin-meta__value muted"><?= htmlspecialchars($createdAt !== '' ? $createdAt : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>
        </ul>

        <div class="cm-auth-divider"></div>

        <div class="cm-admin-reviewbox">
          <div class="cm-admin-reviewbox__label">Message</div>
          <div class="cm-admin-reviewbox__text">
            <?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) ?>
          </div>
        </div>
      </div>

      <!-- Statut -->
      <div class="cm-auth-card cm-admin-card reveal reveal--3">
        <h2 class="cm-admin-card__title">Statut</h2>
        <p class="cm-admin-card__subtitle">Mettre à jour la demande.</p>

        <form method="post" action="<?= BASE_URL ?>/admin/requests/<?= $id ?>">
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
            <button class="cm-btn cm-btn--outline" type="submit">Mettre à jour</button>
            <a class="cm-link-more" href="<?= BASE_URL ?>/admin/requests">
              Retour liste <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </form>

        <div class="cm-auth-divider"></div>

        <p class="cm-auth-note">
          “En cours” = traité, “Terminé” = clôturé.
        </p>
      </div>

    </div>

  </div>
</section>
