<?php
require_once APP_ROOT . '/app/helpers/auth.php';

$user    = auth_user();
$isAuth  = auth_check();
$isAdmin = auth_is_admin();
?>

<header class="cm-header">
  <!-- Main navigation -->
  <nav class="cm-nav">
    <div class="cm-nav__inner">

      <!-- Logo -->
      <a class="cm-brand" href="<?= BASE_URL ?>/">
        <span class="cm-brand__pill">
          CUSTO<span class="cm-brand__o">MOTOR</span>
        </span>
      </a>

      <!-- Burger mobile -->
      <button class="cm-burger" type="button" data-cm-burger aria-label="Ouvrir le menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Menu -->
      <div class="cm-menu" data-cm-menu>

        <!-- Navigation principale -->
        <ul class="cm-links">
          <li>
            <a class="cm-link <?= ($active ?? '') === 'performance' ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/services">
              Performance
            </a>
          </li>

          <li>
            <a class="cm-link <?= ($active ?? '') === 'lookbook' ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/lookbook">
              Lookbook
            </a>
          </li>

          <li>
            <a class="cm-link <?= ($active ?? '') === 'contact' ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/contact">
              Contact
            </a>
          </li>
        </ul>

        <!-- Auth -->
        <div class="cm-auth">
          <?php if ($isAuth): ?>
            <a class="cm-auth__account"
               href="<?= BASE_URL ?>/account"
               aria-label="Accéder à mon compte">
              <i class="fa-regular fa-user" aria-hidden="true"></i>
              <span>Mon compte</span>
            </a>

            <?php if ($isAdmin): ?>
              <a class="cm-auth__account cm-auth__account--admin"
                 href="<?= BASE_URL ?>/admin"
                 aria-label="Accéder à l'administration">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                <span>Admin</span>
              </a>
            <?php endif; ?>

            <a class="cm-auth__logout"
               href="<?= BASE_URL ?>/logout"
               aria-label="Déconnexion">
              <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
              <span>Déconnexion</span>
            </a>
          <?php else: ?>
            <a href="<?= BASE_URL ?>/login">Connexion</a>
            <a href="<?= BASE_URL ?>/register">Inscription</a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </nav>
</header>
