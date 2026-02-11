<?php
// app/views/services/index.php
$title  = 'Performance';
$active = 'performance';


$groups = [];

if (!empty($servicesByCategory) && is_array($servicesByCategory)) {
  $groups = array_values($servicesByCategory);
} elseif (!empty($services) && is_array($services)) {
  $groups = [[
    'category' => 'services',
    'services' => $services,
  ]];
}

// helper slug safe pour ids tabs
$slug = function ($s) {
  $s = strtolower(trim((string)$s));
  $s = preg_replace('~[^a-z0-9]+~', '-', $s);
  return trim($s, '-') ?: 'services';
};
?>

<!-- HERO -->
<section class="perf-hero reveal" style="--perf-hero-img: url('<?= BASE_URL ?>/assets/img/performance-hero.jpg');">
  <div class="perf-hero__overlay"></div>

  <div class="perf-hero__inner">
    <div class="perf-hero__content">
      <p class="perf-hero__kicker reveal reveal--1">OPTIMISATION MOTEUR</p>
      <h1 class="perf-hero__title reveal reveal--2" data-parallax="0.05">Révélez le potentiel de votre véhicule</h1>

      <div class="perf-hero__benefits reveal reveal--3">
        <span class="perf-chip">Cartographie sur mesure</span>
        <span class="perf-chip">Logs & contrôles</span>
        <span class="perf-chip">Conduite optimisée</span>
      </div>

      <div class="perf-hero__actions reveal reveal--3">
        <a class="btn btn--pill" href="<?= BASE_URL ?>/contact">obtenir un devis</a>
        <a class="btn btn--ghost btn--pill" href="<?= BASE_URL ?>/lookbook">voir nos réalisations</a>
      </div>
    </div>
  </div>
</section>

<!-- INTRO -->
<section class="perf-intro reveal">
  <div class="perf-wrap">
    <div class="perf-intro__inner">
      <p>
        Révélez le potentiel insoupçonné de votre véhicule grâce à la reprogrammation moteur.
        Notre service de reprogrammation moteur haut de gamme ne se contente pas d’ajuster les
        paramètres du calculateur de votre véhicule.
      </p>
      <p>
        Nous effectuons une analyse approfondie et une optimisation sur mesure de la cartographie moteur,
        en tenant compte des spécificités de votre véhicule et de vos attentes.
      </p>
      <p>
        Cette approche méticuleuse permet de libérer le potentiel caché de votre moteur,
        se traduisant par une augmentation significative de la puissance et du couple.
        Vous ressentirez une accélération plus vive, des reprises plus franches et une expérience
        de conduite transformée.
      </p>
    </div>
  </div>
</section>

<!-- PROOFS (TRUST) -->
<section class="perf-proof reveal">
  <div class="perf-wrap">
    <div class="perf-proof__inner">
      <div class="perf-proof__grid">
        <article class="perf-proof__card">
          <h3>Cartographie sur mesure</h3>
          <p>Optimisation adaptée à votre moteur et à votre usage, sans approche “générique”.</p>
        </article>

        <article class="perf-proof__card">
          <h3>Contrôles & logs</h3>
          <p>Vérifications avant/après : cohérence, sécurité, stabilité des paramètres.</p>
        </article>

        <article class="perf-proof__card">
          <h3>Résultat ressenti</h3>
          <p>Accélérations plus franches, reprises plus nettes, conduite plus agréable.</p>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- AVANTAGES + IMAGE -->
<section class="perf-split reveal">
  <div class="perf-split__media" style="--perf-img: url('<?= BASE_URL ?>/assets/img/performance-bmw.jpg');"></div>

  <div class="perf-split__content">
    <h2 class="perf-title">
      AVANTAGES DE LA REPROGRAMMATION<br>
      <span class="perf-title__accent">OPTIMISATION</span> MOTEUR<br>
      <span class="perf-title__accent">PERFORMANCES</span> ACCRUES
    </h2>

    <div class="perf-advantages">
      <div class="adv">
        <h3>PERFORMANCES ACCRUES</h3>
        <p>Profitez d’une conduite plus dynamique et de sensations inattendues grâce à une augmentation de la puissance et du couple.</p>
      </div>

      <div class="adv">
        <h3>SÉCURITÉ RENFORCÉE</h3>
        <p>Des accélérations plus franches et des reprises plus vives facilitent les dépassements et améliorent la sécurité sur la route.</p>
      </div>

      <div class="adv">
        <h3>ÉCONOMIES DE CARBURANT</h3>
        <p>L’augmentation du couple moteur permet d’optimiser la combustion et de réduire la consommation de carburant.</p>
      </div>

      <div class="adv">
        <h3>AGRÉMENT DE CONDUITE AMÉLIORÉ</h3>
        <p>Une réponse plus réactive de l’accélérateur et une meilleure souplesse du moteur rendent la conduite plus agréable au quotidien.</p>
      </div>
    </div>
  </div>
</section>

<!-- CHOISIR CUSTOMOTOR + IMAGE -->
<section class="perf-split perf-split--reverse reveal">
  <div class="perf-split__content">
    <h2 class="perf-title">CHOISIR <span class="perf-title__brand">CUSTOMO<span>tor</span></span></h2>

    <ul class="perf-check">
      <li>Analyse des données</li>
      <li>Optimisation</li>
      <li>Tests et contrôle</li>
    </ul>
  </div>

  <div class="perf-split__media" style="--perf-img: url('<?= BASE_URL ?>/assets/img/performance-speedo.jpg');"></div>
</section>

<!-- PROCESS (TIMELINE) -->
<section class="perf-process reveal">
  <div class="perf-wrap">
    <div class="perf-process__inner">
      <div class="perf-process__head">
        <h2 class="perf-process__title">Notre méthode</h2>
        <p class="muted">Une optimisation sérieuse suit un process clair : mesure, réglage, validation.</p>
      </div>

      <div class="perf-steps">
        <article class="perf-step">
          <span class="perf-step__num">01</span>
          <h3 class="perf-step__title">Analyse</h3>
          <p class="perf-step__text">Lecture des données, diagnostic, contrôle des paramètres d’origine.</p>
        </article>

        <article class="perf-step">
          <span class="perf-step__num">02</span>
          <h3 class="perf-step__title">Optimisation</h3>
          <p class="perf-step__text">Réglages sur mesure : injection, turbo, avance, limites de sécurité.</p>
        </article>

        <article class="perf-step">
          <span class="perf-step__num">03</span>
          <h3 class="perf-step__title">Tests & contrôle</h3>
          <p class="perf-step__text">Vérifications, logs et validation pour garantir performance et fiabilité.</p>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES (tabs/filtres + cartes) -->
<section class="perf-services reveal">
  <div class="perf-wrap">

    <div class="pagehead perf-services__head">
      <div>
        <h2>prestations</h2>
        <p class="muted">Des prestations orientées performance et fiabilité, adaptées à votre véhicule et à votre usage.</p>
      </div>

      <div class="pagehead__actions">
        <a class="btn" href="<?= BASE_URL ?>/contact">obtenir un devis</a>
        <a class="btn btn--ghost" href="<?= BASE_URL ?>/lookbook">voir le lookbook</a>
      </div>
    </div>

    <?php if (empty($groups)): ?>
      <section class="surface perf-surface">
        <p class="muted" style="margin:0">Aucun service disponible pour le moment.</p>
      </section>
    <?php else: ?>

      <!-- Tabs Nav -->
      <nav class="perf-tabs-nav" aria-label="Catégories de services">
        <?php foreach ($groups as $i => $group): ?>
          <?php $cat = (string)($group['category'] ?? 'services'); ?>
          <?php $id  = 'tab-' . $slug($cat) . '-' . $i; ?>
          <button
            class="perf-tab-btn <?= $i === 0 ? 'active' : '' ?>"
            type="button"
            data-tab="#<?= htmlspecialchars($id) ?>"
          >
            <?= htmlspecialchars($cat) ?>
          </button>
        <?php endforeach; ?>
      </nav>

      <!-- Tabs Panes -->
      <?php foreach ($groups as $i => $group): ?>
        <?php $cat = (string)($group['category'] ?? 'services'); ?>
        <?php $id  = 'tab-' . $slug($cat) . '-' . $i; ?>

        <section id="<?= htmlspecialchars($id) ?>" class="perf-tab-pane <?= $i === 0 ? 'active' : '' ?>">
          <section class="surface perf-surface">
            <h3 class="perf-cat"><?= htmlspecialchars($cat) ?></h3>

            <section class="servicegrid">
              <?php foreach (($group['services'] ?? []) as $s): ?>
                <article class="servicecard" data-tilt>
                  <header class="servicecard__head">
                    <h4 class="servicecard__title">
                      <?= htmlspecialchars((string)($s['name'] ?? $s['title'] ?? '')) ?>
                    </h4>

                    <?php if (!empty($s['price_from'])): ?>
                      <span class="price">
                        dès <?= number_format((float)$s['price_from'], 0, ',', ' ') ?> €
                      </span>
                    <?php endif; ?>
                  </header>

                  <?php if (!empty($s['description'] ?? $s['desc'] ?? null)): ?>
                    <p class="muted servicecard__desc">
                      <?= htmlspecialchars((string)($s['description'] ?? $s['desc'])) ?>
                    </p>
                  <?php endif; ?>

                  <div class="card__actions">
                    <a class="btn btn--ghost" href="<?= BASE_URL ?>/contact">demander un devis</a>
                  </div>
                </article>
              <?php endforeach; ?>
            </section>
          </section>
        </section>
      <?php endforeach; ?>

    <?php endif; ?>

  </div>
</section>

<!-- TEXTE + LISTE -->
<section class="perf-notes reveal">
  <div class="perf-wrap">
    <div class="perf-notes__inner">
      <p>
        La reprogrammation moteur ajuste les paramètres du calculateur, optimisant puissance et couple.
        Un diagnostic, une modification de la cartographie, et un test final sont effectués :
      </p>

      <ul class="perf-bullets">
        <li><strong>Analyse des données d’origine :</strong> Nos techniciens qualifiés commencent par analyser les données d’origine du calculateur de votre véhicule.</li>
        <li><strong>Optimisation des paramètres :</strong> Ils ajustent ensuite des paramètres tels que l’injection de carburant, la pression du turbo et l’avance à l’allumage.</li>
        <li><strong>Tests et contrôles :</strong> Chaque reprogrammation est soumise à des tests rigoureux et des contrôles (logs) pour garantir performance et fiabilité.</li>
      </ul>

      <div class="perf-cta">
        <a class="btn btn--pill" href="<?= BASE_URL ?>/contact">Obtenir un Devis</a>
      </div>
    </div>
  </div>
</section>
