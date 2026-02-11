<?php
// app/views/admin/index.php
$title  = 'Admin — Dashboard';
$active = 'admin';

$allowedRequestStatus = ['new','in_progress','done','archived'];
$allowedAppointmentStatus = ['pending','confirmed','cancelled'];
$allowedReviewStatus = ['pending','approved','rejected'];

$reqStatus = function ($v) use ($allowedRequestStatus) {
  $s = (string)($v ?? 'new');
  return in_array($s, $allowedRequestStatus, true) ? $s : 'new';
};
$appStatus = function ($v) use ($allowedAppointmentStatus) {
  $s = (string)($v ?? 'pending');
  return in_array($s, $allowedAppointmentStatus, true) ? $s : 'pending';
};
$revStatus = function ($v) use ($allowedReviewStatus) {
  $s = (string)($v ?? 'pending');
  return in_array($s, $allowedReviewStatus, true) ? $s : 'pending';
};

$fullName = function ($first, $last) {
  return trim((string)$first . ' ' . (string)$last);
};

$stats = is_array($stats ?? null) ? $stats : [];
$latestRequests = is_array($latestRequests ?? null) ? $latestRequests : [];
$latestAppointments = is_array($latestAppointments ?? null) ? $latestAppointments : [];
$latestReviews = is_array($latestReviews ?? null) ? $latestReviews : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">Dashboard</h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Vue d’ensemble : demandes, rendez-vous, avis et contenu.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/requests">Demandes</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/appointments">Rendez-vous</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/reviews">Avis</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/projects">Lookbook</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/services">Services</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/logout">
        Déconnexion <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page cm-admin-dash">
  <div class="cm-container">

    <!-- KPI GRID -->
    <div class="reveal reveal--2 cm-admin-dash__kpis">
      <!-- demandes -->
      <div class="cm-auth-card cm-admin-dash__kpi">
        <div class="cm-admin-dash__kpiTop">
          <div class="cm-admin-dash__kpiLabel">demandes (total)</div>
          <div class="cm-admin-dash__kpiValue cm-neon-racing"><?= (int)($stats['requests_total'] ?? 0) ?></div>
        </div>
        <p class="cm-auth-note cm-admin-dash__kpiSub">
          Nouvelles : <strong><?= (int)($stats['requests_new'] ?? 0) ?></strong>
        </p>
        <a class="cm-link-more" href="<?= BASE_URL ?>/admin/requests">
          Voir <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <!-- rendez-vous -->
      <div class="cm-auth-card cm-admin-dash__kpi">
        <div class="cm-admin-dash__kpiTop">
          <div class="cm-admin-dash__kpiLabel">rendez-vous (total)</div>
          <div class="cm-admin-dash__kpiValue cm-neon-racing"><?= (int)($stats['appointments_total'] ?? 0) ?></div>
        </div>
        <p class="cm-auth-note cm-admin-dash__kpiSub">
          En attente : <strong><?= (int)($stats['appointments_pending'] ?? 0) ?></strong>
        </p>
        <a class="cm-link-more" href="<?= BASE_URL ?>/admin/appointments">
          Voir <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <!-- avis -->
      <div class="cm-auth-card cm-admin-dash__kpi">
        <div class="cm-admin-dash__kpiTop">
          <div class="cm-admin-dash__kpiLabel">avis (total)</div>
          <div class="cm-admin-dash__kpiValue cm-neon-racing"><?= (int)($stats['reviews_total'] ?? 0) ?></div>
        </div>
        <p class="cm-auth-note cm-admin-dash__kpiSub">
          À modérer : <strong><?= (int)($stats['reviews_pending'] ?? 0) ?></strong>
        </p>
        <a class="cm-link-more" href="<?= BASE_URL ?>/admin/reviews">
          Voir <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <!-- lookbook -->
      <div class="cm-auth-card cm-admin-dash__kpi">
        <div class="cm-admin-dash__kpiTop">
          <div class="cm-admin-dash__kpiLabel">lookbook</div>
          <div class="cm-admin-dash__kpiValue cm-neon-racing"><?= (int)($stats['projects_total'] ?? 0) ?></div>
        </div>
        <p class="cm-auth-note cm-admin-dash__kpiSub">Projets publiés</p>
        <a class="cm-link-more" href="<?= BASE_URL ?>/admin/projects">
          Gérer <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <!-- services -->
<div class="cm-auth-card cm-admin-dash__kpi">
  <div class="cm-admin-dash__kpiTop">
    <div class="cm-admin-dash__kpiLabel">services (total)</div>
    <div class="cm-admin-dash__kpiValue cm-neon-racing"><?= (int)($stats['services_total'] ?? 0) ?></div>
  </div>
  <p class="cm-auth-note cm-admin-dash__kpiSub">Prestations actives</p>
  <a class="cm-link-more" href="<?= BASE_URL ?>/admin/services">
    Gérer <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
  </a>
</div>

    </div>

    <!-- LISTS GRID -->
    <div class="reveal reveal--3 cm-admin-dash__grid">
      <!-- dernières demandes (large) -->
      <div class="cm-auth-card cm-admin-dash__card">
        <h2 class="cm-admin-dash__title">Dernières demandes</h2>
        <p class="cm-auth-note cm-admin-dash__subtitle">Accès rapide à la modération et au suivi.</p>

        <?php if (empty($latestRequests)): ?>
          <p class="cm-auth-note" style="margin:0;">Aucune demande.</p>
        <?php else: ?>
          <div class="cm-admin-dash__list">
            <?php foreach ($latestRequests as $r): ?>
              <?php $st = $reqStatus($r['status'] ?? 'new'); ?>
              <div class="cm-admin-dash__item">
                <div class="cm-admin-dash__itemBody">
                  <div class="cm-admin-dash__itemTop">
                    <a class="cm-link-more" href="<?= BASE_URL ?>/admin/requests/<?= (int)($r['id'] ?? 0) ?>">
                      #<?= (int)($r['id'] ?? 0) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <span class="cm-admin-dash__name">
                      <?= htmlspecialchars($fullName($r['firstname'] ?? '', $r['lastname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  </div>
                  <div class="muted cm-admin-dash__meta">
                    <?= htmlspecialchars((string)($r['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                  </div>
                </div>

                <span class="status status--<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="cm-auth-divider"></div>
          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/requests">
            Voir toutes les demandes <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>

      <!-- derniers rendez-vous -->
      <div class="cm-auth-card cm-admin-dash__card">
        <h2 class="cm-admin-dash__title">Derniers rendez-vous</h2>
        <p class="cm-auth-note cm-admin-dash__subtitle cm-admin-dash__subtitle--tight">Validation / annulation rapide.</p>

        <?php if (empty($latestAppointments)): ?>
          <p class="cm-auth-note" style="margin:0;">Aucun rendez-vous.</p>
        <?php else: ?>
          <div class="cm-admin-dash__list cm-admin-dash__list--tight">
            <?php foreach ($latestAppointments as $a): ?>
              <?php $st = $appStatus($a['status'] ?? 'pending'); ?>
              <div class="cm-admin-dash__item cm-admin-dash__item--tight">
                <div class="cm-admin-dash__itemBody">
                  <a class="cm-link-more" href="<?= BASE_URL ?>/admin/appointments/<?= (int)($a['id'] ?? 0) ?>/edit">
                    #<?= (int)($a['id'] ?? 0) ?> <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                  </a>
                  <div class="cm-admin-dash__name">
                    <?= htmlspecialchars($fullName($a['firstname'] ?? '', $a['lastname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                  </div>
                  <div class="muted cm-admin-dash__meta cm-admin-dash__meta--sm">
                    <?= htmlspecialchars((string)($a['requested_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                  </div>
                </div>

                <span class="status status--<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="cm-admin-dash__afterTight"></div>
          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/appointments">
            Voir tous les rendez-vous <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>

      <!-- derniers avis -->
      <div class="cm-auth-card cm-admin-dash__card">
        <h2 class="cm-admin-dash__title">Derniers avis</h2>
        <p class="cm-auth-note cm-admin-dash__subtitle">Modération des retours clients.</p>

        <?php if (empty($latestReviews)): ?>
          <p class="cm-auth-note" style="margin:0;">Aucun avis.</p>
        <?php else: ?>
          <div class="cm-admin-dash__list">
            <?php foreach ($latestReviews as $rv): ?>
              <?php $st = $revStatus($rv['status'] ?? 'pending'); ?>
              <div class="cm-admin-dash__item">
                <div class="cm-admin-dash__itemBody">
                  <a class="cm-link-more" href="<?= BASE_URL ?>/admin/reviews/<?= (int)($rv['id'] ?? 0) ?>/edit">
                    #<?= (int)($rv['id'] ?? 0) ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                  </a>
                  <div class="cm-admin-dash__name">
                    <?= htmlspecialchars($fullName($rv['firstname'] ?? '', $rv['lastname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                  </div>
                  <div class="muted cm-admin-dash__meta">
                    <?= (int)($rv['rating'] ?? 0) ?>/5
                  </div>
                </div>

                <span class="status status--<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="cm-auth-divider"></div>
          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/reviews">
            Voir tous les avis <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>
