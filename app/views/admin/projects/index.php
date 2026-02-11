<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Lookbook';
$active = 'admin_projects';

$projects = is_array($projects ?? null) ? $projects : [];

$created = !empty($created);
$updated = !empty($updated);
$deleted = !empty($deleted);
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Lookbook
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Créer et modifier les projets affichés sur le lookbook.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin">Dashboard</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/projects/create">
        Nouveau projet
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <?php if ($created): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong>
        Projet créé.
      </div>
    <?php endif; ?>

    <?php if ($updated): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong>
        Projet mis à jour.
      </div>
    <?php endif; ?>

    <?php if ($deleted): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong>
        Projet supprimé.
      </div>
    <?php endif; ?>

    <div class="cm-auth-card cm-admin-card reveal reveal--2">
      <div class="cm-admin-head">
        <h2 class="cm-admin-card__title">Projets</h2>
        <p class="cm-admin-card__subtitle">
          Gère le contenu public du lookbook.
        </p>
      </div>

      <?php if (empty($projects)): ?>
        <p class="cm-admin-empty">Aucun projet.</p>
      <?php else: ?>

        <div class="cm-admin-toolbar">
          <div class="cm-admin-toolbar__hint muted"><?= count($projects) ?> projets</div>
          <a class="cm-link-more" href="<?= BASE_URL ?>/lookbook">
            Voir le lookbook public <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <div class="cm-admin-table">
          <div style="overflow:auto">
            <table class="table">
              <thead>
                <tr>
                  <th>id</th>
                  <th>titre</th>
                  <th>sous-titre</th>
                  <th>actions</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($projects as $p): ?>
                  <?php $id = (int)($p['id'] ?? 0); ?>
                  <tr>
                    <td>
                      <a class="cm-link-more" href="<?= BASE_URL ?>/lookbook/<?= $id ?>">
                        #<?= $id ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                      </a>
                    </td>

                    <td><?= htmlspecialchars((string)($p['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="muted"><?= htmlspecialchars((string)($p['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>

                    <td style="min-width:320px">
                      <div style="display:flex; gap:.6rem; align-items:center; flex-wrap:wrap">
                        <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/projects/<?= $id ?>/edit">
                          Modifier
                        </a>

                        <a class="btn btn--ghost" href="<?= BASE_URL ?>/lookbook/<?= $id ?>" style="text-decoration:none">
                          Voir public
                        </a>

                        <form method="post" action="<?= BASE_URL ?>/admin/projects/<?= $id ?>/delete" onsubmit="return confirm('Supprimer ce projet ?')">
                          <?= csrf_field() ?>
                          <button class="btn btn--ghost" type="submit">Supprimer</button>
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
