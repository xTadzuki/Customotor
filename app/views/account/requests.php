<?php
$title  = 'Mes demandes';
$active = 'account';

$requests = is_array($requests ?? null) ? $requests : [];

$fmtDate = function (?string $dt): string {
  if (!$dt) return '—';
  $ts = strtotime($dt);
  return $ts ? date('d/m/Y', $ts) : htmlspecialchars($dt);
};

$statusLabel = function (string $s): string {
  $s = strtolower(trim($s));
  return match ($s) {
    'answered' => 'Répondu',
    'closed'   => 'Clos',
    default    => 'En attente',
  };
};

$statusClass = function (string $s): string {
  $s = strtolower(trim($s));
  return match ($s) {
    'answered' => 'cm-badge cm-badge--ok',
    'closed'   => 'cm-badge cm-badge--muted',
    default    => 'cm-badge cm-badge--warn',
  };
};
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Mes demandes
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Retrouvez l’historique de vos demandes et leur statut.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/account">Retour compte</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/contact">Nouvelle demande</a>
    </div>
  </div>
</section>

<section class="cm-account">
  <div class="cm-container">

    <div class="cm-account__card reveal reveal--2">

      <div class="cm-table-head">
        <div>
          <h2 class="cm-account__h2" style="margin:0;">Historique</h2>
          <p class="cm-account__p" style="margin:.35rem 0 0;">
            Vous verrez ici la date, le véhicule, le sujet et l’état de traitement.
          </p>
        </div>

        <a class="cm-link-more" href="<?= BASE_URL ?>/contact">
          Nouvelle demande <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <?php if (empty($requests)): ?>
        <div class="cm-empty">
          <div class="cm-empty__icon" aria-hidden="true">—</div>
          <div>
            <p class="cm-empty__title">Aucune demande pour le moment</p>
            <p class="cm-empty__text">
              Lancez une première demande via le formulaire de contact.
            </p>
          </div>
        </div>
      <?php else: ?>
        <div class="cm-table-wrap" role="region" aria-label="Table des demandes" tabindex="0">
          <table class="cm-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Véhicule</th>
                <th>Sujet</th>
                <th>Statut</th>
                <th class="cm-th-right">Action</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($requests as $r): ?>
                <?php
                  $id = (int)($r['id'] ?? 0);
                  $date = $fmtDate((string)($r['created_at'] ?? ''));
                  $vehicle = (string)($r['vehicle'] ?? '—');
                  $subject = (string)($r['subject'] ?? '—');
                  $status = (string)($r['status'] ?? 'pending');
                ?>
                <tr>
                  <td class="cm-td-muted"><?= htmlspecialchars($date) ?></td>
                  <td><?= htmlspecialchars($vehicle) ?></td>
                  <td><?= htmlspecialchars($subject) ?></td>
                  <td>
                    <span class="<?= htmlspecialchars($statusClass($status)) ?>">
                      <?= htmlspecialchars($statusLabel($status)) ?>
                    </span>
                  </td>
                  <td class="cm-td-right">
                    <a class="cm-table-link" href="<?= BASE_URL ?>/account/requests/<?= $id ?>">
                      Voir <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p class="cm-account__fineprint" style="margin-top:1.1rem;">
          Astuce : le statut passe automatiquement à “Répondu” quand une réponse est enregistrée côté admin.
        </p>
      <?php endif; ?>

    </div>

  </div>
</section>
