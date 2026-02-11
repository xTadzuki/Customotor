<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Mes rendez-vous';
$active = 'account';

$success = (bool)($success ?? false);

$old    = is_array($old ?? null) ? $old : [];
$errors = is_array($errors ?? null) ? $errors : [];

$appointments = is_array($appointments ?? null) ? $appointments : [];

// status whitelist (évite classes css invalides)
$allowedStatus = ['pending', 'confirmed', 'cancelled'];

$statusLabel = function(string $s): string {
  $s = strtolower(trim($s));
  return match($s){
    'confirmed' => 'Confirmé',
    'cancelled' => 'Annulé',
    default     => 'En attente',
  };
};

$statusClass = function(string $s): string {
  $s = strtolower(trim($s));
  return match($s){
    'confirmed' => 'cm-badge cm-badge--ok',
    'cancelled' => 'cm-badge cm-badge--muted',
    default     => 'cm-badge cm-badge--warn',
  };
};

$fmtDateTime = function (?string $dt): string {
  if (!$dt) return '—';
  $ts = strtotime($dt);
  return $ts ? date('d/m/Y H:i', $ts) : $dt;
};
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Mes rendez-vous
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Demandez un rendez-vous, puis suivez son statut (validation par l’admin).
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/account">Retour compte</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/logout">Déconnexion</a>
    </div>
  </div>
</section>

<section class="cm-account">
  <div class="cm-container">

    <?php if ($success): ?>
      <div class="cm-alert cm-alert--success reveal reveal--1">
        <strong>Demande envoyée</strong>
        Votre demande de rendez-vous a bien été transmise.
      </div>
    <?php endif; ?>

    <div class="cm-account__grid">

      <!-- FORM -->
      <div class="cm-account__card reveal reveal--2">
        <h2 class="cm-account__h2">Demander un rendez-vous</h2>
        <p class="cm-account__p">
          Choisissez une date/heure souhaitées, ajoutez une note si nécessaire.
        </p>

        <form method="post" action="<?= BASE_URL ?>/account/appointments" novalidate>
          <?= csrf_field() ?>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="requested_at">Date & heure souhaitées *</label>
              <input
                id="requested_at"
                class="cm-input"
                type="datetime-local"
                name="requested_at"
                value="<?= htmlspecialchars((string)($old['requested_at'] ?? '')) ?>"
                required
              >
              <?php if (!empty($errors['requested_at'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['requested_at']) ?></small>
              <?php endif; ?>
            </div>

            <div class="cm-field">
              <label class="cm-label" for="note">Note (optionnel)</label>
              <textarea
                id="note"
                class="cm-textarea"
                name="note"
                rows="3"
              ><?= htmlspecialchars((string)($old['note'] ?? '')) ?></textarea>

              <?php if (!empty($errors['note'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['note']) ?></small>
              <?php else: ?>
                <small class="cm-auth-note" style="display:block; margin-top:.45rem;">
                  Ex : stage 1, diagnostic, bruit échappement…
                </small>
              <?php endif; ?>
            </div>
          </div>

          <div class="cm-form__actions">
            <button class="cm-btn cm-btn--outline" type="submit">
              Demander un rendez-vous
            </button>
          </div>

          <p class="cm-account__fineprint" style="margin-top:1rem;">
            Un rendez-vous est “Confirmé” uniquement après validation par l’admin.
          </p>
        </form>
      </div>

      <!-- LIST / TABLE -->
      <div class="cm-account__card reveal reveal--3">
        <div class="cm-table-head">
          <div>
            <h2 class="cm-account__h2" style="margin:0;">Historique</h2>
            <p class="cm-account__p" style="margin:.35rem 0 0;">
              Vos rendez-vous passés et à venir.
            </p>
          </div>
        </div>

        <?php if (empty($appointments)): ?>
          <div class="cm-empty">
            <div class="cm-empty__icon" aria-hidden="true">
              <i class="fa-regular fa-calendar" aria-hidden="true"></i>
            </div>
            <div>
              <p class="cm-empty__title">Aucun rendez-vous</p>
              <p class="cm-empty__text">Faites une première demande via le formulaire.</p>
            </div>
          </div>
        <?php else: ?>
          <div class="cm-table-wrap" role="region" aria-label="Table des rendez-vous" tabindex="0">
            <table class="cm-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date souhaitée</th>
                  <th>Note</th>
                  <th>Statut</th>
                  <th class="cm-th-right">Créé le</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($appointments as $a): ?>
                  <?php
                    $st = (string)($a['status'] ?? 'pending');
                    if (!in_array($st, $allowedStatus, true)) $st = 'pending';

                    $id = (int)($a['id'] ?? 0);
                    $requestedAt = $fmtDateTime((string)($a['requested_at'] ?? ''));
                    $createdAt   = $fmtDateTime((string)($a['created_at'] ?? ''));
                    $note        = trim((string)($a['note'] ?? ''));
                  ?>
                  <tr>
                    <td class="cm-td-muted">#<?= $id ?></td>
                    <td><?= htmlspecialchars($requestedAt) ?></td>
                    <td class="cm-td-muted"><?= htmlspecialchars($note !== '' ? $note : '—') ?></td>
                    <td>
                      <span class="<?= htmlspecialchars($statusClass($st)) ?>">
                        <?= htmlspecialchars($statusLabel($st)) ?>
                      </span>
                    </td>
                    <td class="cm-td-right cm-td-muted"><?= htmlspecialchars($createdAt) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>
