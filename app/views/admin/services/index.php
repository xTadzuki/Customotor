<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Services';
$active = 'admin_services';

$created = (bool)($created ?? (isset($_GET['created']) && $_GET['created'] === '1'));
$updated = (bool)($updated ?? (isset($_GET['updated']) && $_GET['updated'] === '1'));
$deleted = (bool)($deleted ?? (isset($_GET['deleted']) && $_GET['deleted'] === '1'));

$services = is_array($services ?? null) ? $services : [];

function clip(string $text, int $max = 120): string {
  $t = trim($text);
  if (mb_strlen($t) <= $max) return $t;
  return mb_substr($t, 0, $max) . '…';
}

$labelActive = function (int $v): string {
  return $v === 1 ? 'Actif' : 'Inactif';
};

$statusClass = function (int $v): string {
  
  return $v === 1 ? 'confirmed' : 'cancelled';
};
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Services
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Gestion des prestations : création, édition, désactivation.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin">Dashboard</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/services/create">Nouveau service</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/logout">
        Déconnexion <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <?php if ($created): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong> Service créé.
      </div>
    <?php endif; ?>

    <?php if ($updated): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong> Service mis à jour.
      </div>
    <?php endif; ?>

    <?php if ($deleted): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong> Service désactivé.
      </div>
    <?php endif; ?>

    <div class="cm-auth-card cm-admin-card reveal reveal--2">

      <div class="cm-admin-head">
        <h2 class="cm-admin-card__title">Liste des services</h2>
        <p class="cm-admin-card__subtitle">Clique pour modifier, ou désactive un service.</p>
      </div>

      <?php if (empty($services)): ?>
        <p class="cm-admin-empty">Aucun service.</p>
      <?php else: ?>

        <div class="cm-admin-toolbar">
          <div class="cm-admin-toolbar__hint muted">
            <?= count($services) ?> services
          </div>
        </div>

        <div class="cm-admin-table">
          <div style="overflow:auto">
            <table class="table">
              <thead>
                <tr>
                  <th>id</th>
                  <th>nom</th>
                  <th>catégorie</th>
                  <th>prix dès</th>
                  <th>ordre</th>
                  <th>statut</th>
                  <th>actions</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($services as $s): ?>
                  <?php
                    $id = (int)($s['id'] ?? 0);
                    $name = (string)($s['name'] ?? '');
                    $cat  = (string)($s['category_name'] ?? '—');
                    $descFull  = (string)($s['description'] ?? '');
                    $descShort = $descFull !== '' ? clip($descFull, 110) : '';

                    $price = $s['price_from'];
                    $order = (int)($s['sort_order'] ?? 0);

                    $activeVal = (int)($s['is_active'] ?? 1);
                    $stClass = $statusClass($activeVal);
                    $stText  = $labelActive($activeVal);
                  ?>
                  <tr>
                    <td>
                      <a class="cm-link-more" href="<?= BASE_URL ?>/admin/services/<?= $id ?>/edit">
                        #<?= $id ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                      </a>
                    </td>

                    <td>
                      <strong><?= htmlspecialchars($name !== '' ? $name : '—', ENT_QUOTES, 'UTF-8') ?></strong>
                      <?php if ($descShort !== ''): ?>
                        <div class="muted" title="<?= htmlspecialchars($descFull, ENT_QUOTES, 'UTF-8') ?>">
                          <?= htmlspecialchars($descShort, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                      <?php endif; ?>
                    </td>

                    <td class="muted"><?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                      <?php if ($price !== null && $price !== ''): ?>
                        <?= number_format((float)$price, 0, ',', ' ') ?> €
                      <?php else: ?>
                        <span class="muted">—</span>
                      <?php endif; ?>
                    </td>

                    <td><?= $order ?></td>

                    <td>
                      <span class="status status--<?= htmlspecialchars($stClass, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($stText, ENT_QUOTES, 'UTF-8') ?>
                      </span>
                    </td>

                    <td class="cm-admin-actions">
  <div class="cm-admin-actionsRow">
    <a class="cm-btn cm-btn--outline cm-btn--sm" href="<?= BASE_URL ?>/admin/services/<?= $id ?>/edit">
      Éditer
    </a>

    <form class="cm-admin-inlineform" method="post" action="<?= BASE_URL ?>/admin/services/<?= $id ?>/delete">
      <?= csrf_field() ?>
      <button class="cm-btn cm-btn--outline cm-btn--sm" type="submit"
              onclick="return confirm('Désactiver ce service ?');">
        Désactiver
      </button>
    </form>
  </div>
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
