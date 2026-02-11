<?php
// app/views/layouts/footer.php
?>
<footer class="cm-footer">

  <div class="cm-footer__main">
    <div class="cm-footer__inner">

      <div class="cm-footer__brand">
        <div class="cm-footer__logo">
          CUSTOM<span class="cm-footer__o">O</span>TOR
        </div>
        <p class="cm-footer__tagline">
          Performance & préparation sur mesure.
        </p>
      </div>

      <div class="cm-footer__cols">

        <div class="cm-footer__col">
          <h4>Navigation</h4>
          <ul>
            <li><a class="cm-link" href="<?= BASE_URL ?>/">Accueil</a></li>
            <li><a class="cm-link" href="<?= BASE_URL ?>/services">Services</a></li>
            <li><a class="cm-link" href="<?= BASE_URL ?>/lookbook">Lookbook</a></li>
            <li><a class="cm-link" href="<?= BASE_URL ?>/contact">Contact</a></li>
          </ul>
        </div>

        <div class="cm-footer__col">
          <h4>Contact</h4>
          <ul>
            <li>📍 Customotor - 6 impasse des adrets Venelles 13770</li>
            <li>📞 06 75 91 27 86</li>
            <li>✉️ contact@customotor.fr</li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="cm-footer__bottom">
    <div class="cm-footer__bottom-inner">

      <p>© <?= date('Y') ?> Customotor — Tous droits réservés.</p>

      <!-- Liens légaux ajoutés ici -->
      <nav class="cm-footer__legal" aria-label="Liens légaux">
        <a href="<?= BASE_URL ?>/mentions-legales">Mentions légales</a>
        <a href="<?= BASE_URL ?>/cgv">CGV</a>
        <a href="<?= BASE_URL ?>/confidentialite">Confidentialité</a>
      </nav>

      <div class="cm-footer__social">
        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
      </div>

    </div>
  </div>

</footer>
