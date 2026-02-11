<?php
// app/views/home/index.php
$title  = 'Accueil';
$active = 'home'; 
?>

<section class="cm-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-hero__overlay"></div>

  <div class="cm-container cm-hero__inner">
    <div class="cm-hero__content">
      <p class="cm-hero__kicker reveal reveal--1">Performance</p>

      <h1 class="cm-hero__title cm-neon-racing cm-neon-racing--animated cm-ignition reveal reveal--2">
        Et Préparation Automobile
      </h1>

      <a class="cm-btn cm-btn--outline reveal reveal--3" href="<?= BASE_URL ?>/contact">
        Nous contacter
      </a>
    </div>
  </div>
</section>

<section class="cm-lookbook">
  <div class="cm-container cm-lookbook__head">
    <div class="cm-lookbook__titles">
      <p class="cm-section-kicker reveal reveal--1">PARCOUREZ</p>
      <h2 class="cm-section-title cm-neon-racing reveal reveal--2">LE LOOK BOOK</h2>
    </div>

    <a class="cm-link-more reveal reveal--3" href="<?= BASE_URL ?>/lookbook">
      Voir tout <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
    </a>
  </div>

  <div class="cm-container">
    <div class="cm-carousel" data-carousel>

      <button class="cm-carousel__arrow cm-carousel__arrow--left"
              aria-label="Précédent"
              type="button"
              data-prev>
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M15 18l-6-6 6-6"
                fill="none"
                stroke="currentColor"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"/>
        </svg>
      </button>

      <div class="cm-carousel__viewport">
        <div class="cm-carousel__track" data-track>
          <a class="cm-carousel__item"
             href="<?= BASE_URL ?>/lookbook"
             style="--img:url('<?= BASE_URL ?>/assets/img/look1.jpg');"></a>

          <a class="cm-carousel__item"
             href="<?= BASE_URL ?>/lookbook"
             style="--img:url('<?= BASE_URL ?>/assets/img/look2.jpg');"></a>

          <a class="cm-carousel__item"
             href="<?= BASE_URL ?>/lookbook"
             style="--img:url('<?= BASE_URL ?>/assets/img/look3.jpg');"></a>

          <a class="cm-carousel__item"
             href="<?= BASE_URL ?>/lookbook"
             style="--img:url('<?= BASE_URL ?>/assets/img/look4.jpg');"></a>
        </div>
      </div>

      <button class="cm-carousel__arrow cm-carousel__arrow--right"
              aria-label="Suivant"
              type="button"
              data-next>
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M9 6l6 6-6 6"
                fill="none"
                stroke="currentColor"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"/>
        </svg>
      </button>

    </div>
  </div>
</section>
