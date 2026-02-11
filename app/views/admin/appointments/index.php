<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Rendez-vous';
$active = 'admin_appointments';

$updated = (bool)($updated ?? (isset($_GET['updated']) && $_GET['updated'] === '1'));
$allowedStatus = ['pending', 'confirmed', 'cancelled'];

$appointments = is_array($appointments ?? null) ? $appointments : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Rendez-vous
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Gestion des demandes de rendez-vous (statuts : pending / confirmed / cancelled).
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin">
        Dashboard
      </a>
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
        <strong>Statut mis à jour</strong>
        La demande a bien été enregistrée.
      </div>
    <?php endif; ?>

    <div class="cm-auth-card reveal reveal--2">

      <?php if (empty($appointments)): ?>
        <p class="cm-auth-note" style="margin:0">
          Aucune demande de rendez-vous pour le moment.
        </p>
      <?php else: ?>
        <div style="overflow:auto">
          <table class="table">
            <thead>
              <tr>
                <th>id</th>
                <th>client</th>
                <th>email</th>
                <th>date souhaitée</th>
                <th>note</th>
                <th>statut</th>
                <th>action</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($appointments as $a): ?>
                <?php
                  $id = (int)($a['id'] ?? 0);

                  $st = (string)($a['status'] ?? 'pending');
                  if (!in_array($st, $allowedStatus, true)) $st = 'pending';

                  $client = trim((string)($a['firstname'] ?? '') . ' ' . (string)($a['lastname'] ?? ''));
                  $email  = (string)($a['email'] ?? '');
                  $date   = (string)($a['requested_at'] ?? '');
                  $note   = (string)($a['note'] ?? '');
                ?>
                <tr>
                  <td>
                    <a class="cm-link-more" href="<?= BASE_URL ?>/admin/appointments/<?= $id ?>/edit">
                      #<?= $id ?> <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                    </a>
                  </td>

                  <td><?= htmlspecialchars($client !== '' ? $client : '—') ?></td>
                  <td class="muted"><?= htmlspecialchars($email !== '' ? $email : '—') ?></td>
                  <td><?= htmlspecialchars($date !== '' ? $date : '—') ?></td>
                  <td class="muted"><?= htmlspecialchars($note !== '' ? $note : '—') ?></td>

                  <td>
                    <span class="status status--<?= htmlspecialchars($st) ?>">
                      <?= htmlspecialchars($st) ?>
                    </span>
                  </td>

                  <td>
                    <form method="post" action="<?= BASE_URL ?>/admin/appointments/<?= $id ?>" style="display:flex; gap:.6rem; align-items:center; flex-wrap:wrap">
                      <?= csrf_field() ?>

                      <select class="cm-input" name="status" required style="min-width:190px; padding:.65rem .9rem;">
                        <?php foreach ($allowedStatus as $v): ?>
                          <option value="<?= htmlspecialchars($v) ?>" <?= $st === $v ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>

                      <button class="cm-btn cm-btn--outline" type="submit" style="padding:.65rem 1.2rem;">
                        Enregistrer
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p class="cm-auth-note" style="margin:1rem 0 0">
          Astuce : cliquez sur un <strong>#id</strong> pour ouvrir la page d’édition dédiée.
        </p>
      <?php endif; ?>

    </div>
  </div>
</section>
