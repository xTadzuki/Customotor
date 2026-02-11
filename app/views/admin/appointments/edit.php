<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Éditer rendez-vous';
$active = 'admin_appointments';

if (empty($appointment)) {
  http_response_code(404);
  echo '404';
  exit;
}

$allowedStatus = ['pending','confirmed','cancelled'];
$current = (string)($appointment['status'] ?? 'pending');
if (!in_array($current, $allowedStatus, true)) $current = 'pending';

$id     = (int)($appointment['id'] ?? 0);
$client = trim((string)($appointment['firstname'] ?? '') . ' ' . (string)($appointment['lastname'] ?? ''));
$date   = (string)($appointment['requested_at'] ?? '');
$note   = (string)($appointment['note'] ?? '');
$email  = (string)($appointment['email'] ?? '');
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Rendez-vous #<?= $id ?>
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Modification du statut.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/appointments">
        Retour
      </a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/admin">
        Dashboard <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <div class="cm-auth-card reveal reveal--2">

      <div class="cm-alert cm-alert--info" style="margin-bottom:1.25rem">
        <strong>Infos client</strong>
        <div style="margin-top:.35rem; line-height:1.7">
          Client : <strong><?= htmlspecialchars($client !== '' ? $client : '—') ?></strong><br>
          Email : <strong><?= htmlspecialchars($email !== '' ? $email : '—') ?></strong><br>
          Date souhaitée : <strong><?= htmlspecialchars($date !== '' ? $date : '—') ?></strong><br>
          Note : <span class="muted"><?= htmlspecialchars($note !== '' ? $note : '—') ?></span>
        </div>
      </div>

      <form method="post" action="<?= BASE_URL ?>/admin/appointments/<?= $id ?>" novalidate>
        <?= csrf_field() ?>

        <div class="cm-field">
          <label class="cm-label" for="status">Statut</label>

          <select id="status" class="cm-input" name="status" required style="padding:.75rem 1rem;">
            <?php foreach ($allowedStatus as $st): ?>
              <option value="<?= htmlspecialchars($st) ?>" <?= $current === $st ? 'selected' : '' ?>>
                <?= htmlspecialchars($st) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <small class="cm-auth-note" style="display:block; margin-top:.55rem;">
            <span class="cm-auth-note__label">Rappel :</span>
            pending = en attente • confirmed = validé • cancelled = annulé
          </small>
        </div>

        <div class="cm-auth-actions" style="margin-top:1rem">
          <button class="cm-btn cm-btn--outline" type="submit">
            Enregistrer
          </button>

          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/appointments">
            Retour liste <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </form>

    </div>
  </div>
</section>
