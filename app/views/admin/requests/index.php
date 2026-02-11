<?php
$title  = 'Admin — Demandes';
$active = 'admin_requests';

$requests = is_array($requests ?? null) ? $requests : [];

$allowedStatus = ['new', 'in_progress', 'done'];

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
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Demandes
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Liste des demandes reçues via le formulaire de contact.
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

    <div class="cm-auth-card cm-admin-card reveal reveal--2">
      <div class="cm-admin-head">
        <h2 class="cm-admin-card__title">Toutes les demandes</h2>
        <p class="cm-admin-card__subtitle">
          Clique sur une demande pour voir le message complet et changer le statut.
        </p>
      </div>

      <?php if (empty($requests)): ?>
        <p class="cm-admin-empty">Aucune demande pour le moment.</p>
      <?php else: ?>

        <div class="cm-admin-toolbar">
          <div class="cm-admin-toolbar__hint muted"><?= count($requests) ?> demandes</div>
        </div>

        <div class="cm-admin-table">
          <div style="overflow:auto">
            <table class="table">
              <thead>
                <tr>
                  <th>id</th>
                  <th>client</th>
                  <th>email</th>
                  <th>véhicule</th>
                  <th>statut</th>
                  <th>date</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($requests as $r): ?>
                  <?php
                    $id = (int)($r['id'] ?? 0);

                    $st = (string)($r['status'] ?? 'new');
                    if (!in_array($st, $allowedStatus, true)) $st = 'new';

                    $clientName = trim((string)($r['firstname'] ?? '') . ' ' . (string)($r['lastname'] ?? ''));
                    $email = (string)($r['email'] ?? '');
                    $vehicle = trim((string)($r['brand'] ?? '') . ' ' . (string)($r['model'] ?? ''));
                    $year = $r['year'] ?? null;

                    $statusClass = $badgeClass($st);
                    $statusText  = $labelStatus($st);
                  ?>
                  <tr>
                    <td>
                      <a class="cm-link-more" href="<?= BASE_URL ?>/admin/requests/<?= $id ?>">
                        #<?= $id ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                      </a>
                    </td>

                    <td><?= htmlspecialchars($clientName !== '' ? $clientName : '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="muted"><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                      <?= htmlspecialchars($vehicle !== '' ? $vehicle : '—', ENT_QUOTES, 'UTF-8') ?>
                      <?php if (!empty($year)): ?>
                        <span class="muted"> (<?= (int)$year ?>)</span>
                      <?php endif; ?>
                    </td>

                    <td>
                      <span class="status status--<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') ?>
                      </span>
                    </td>

                    <td class="muted"><?= htmlspecialchars((string)($r['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
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
