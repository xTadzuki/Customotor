<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/helpers/flash.php';
require_once APP_ROOT . '/app/models/user.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class AuthController extends Controller
{
    public function login(): void
    {
        User::ensureAdminSeed();

        $this->view('auth/login', [
            'title'  => 'customotor - connexion',
            'errors' => [],
            'old'    => [],
            'flash'  => flash_get('info'),
        ]);
    }

    public function authenticate(): void
    {
        User::ensureAdminSeed();
        csrf_verify();

        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email invalide';
        }
        if ($password === '') {
            $errors['password'] = 'mot de passe requis';
        }

        if ($errors) {
            $this->view('auth/login', [
                'title'  => 'customotor - connexion',
                'errors' => $errors,
                'old'    => ['email' => $email],
                'flash'  => null,
            ]);
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, (string)($user['password_hash'] ?? ''))) {
            $this->view('auth/login', [
                'title'  => 'customotor - connexion',
                'errors' => ['global' => 'identifiants incorrects'],
                'old'    => ['email' => $email],
                'flash'  => null,
            ]);
            return;
        }

        // anti session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        auth_login($user);

        // redirection simple : admin -> /admin, client -> /account
        $role = (string)($user['role'] ?? 'client');
        $target = ($role === 'admin') ? (BASE_URL . '/admin') : (BASE_URL . '/account');

        header('Location: ' . $target);
        exit;
    }

    public function register(): void
    {
        $this->view('auth/register', [
            'title'  => 'customotor - inscription',
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): void
    {
        csrf_verify();

        $data = [
            'firstname' => trim((string)($_POST['firstname'] ?? '')),
            'lastname'  => trim((string)($_POST['lastname'] ?? '')),
            'email'     => trim((string)($_POST['email'] ?? '')),
            'password'  => (string)($_POST['password'] ?? ''),
        ];

        $errors = [];

        if ($data['firstname'] === '' || mb_strlen($data['firstname']) < 2) {
            $errors['firstname'] = 'prénom requis (2 caractères min)';
        }
        if ($data['lastname'] === '' || mb_strlen($data['lastname']) < 2) {
            $errors['lastname'] = 'nom requis (2 caractères min)';
        }
        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email invalide';
        }
        if (mb_strlen($data['password']) < 8) {
            $errors['password'] = '8 caractères minimum';
        }

        if (!$errors && User::findByEmail($data['email'])) {
            $errors['email'] = 'email déjà utilisé';
        }

        if ($errors) {
            $this->view('auth/register', [
                'title'  => 'customotor - inscription',
                'errors' => $errors,
                'old'    => [
                    'firstname' => $data['firstname'],
                    'lastname'  => $data['lastname'],
                    'email'     => $data['email'],
                ],
            ]);
            return;
        }

        $user = User::create([
            'role'          => 'client',
            'firstname'     => $data['firstname'],
            'lastname'      => $data['lastname'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        auth_login($user);
        flash_set('info', 'compte créé avec succès');

        header('Location: ' . BASE_URL . '/account');
        exit;
    }

    public function logout(): void
    {
        
        auth_logout();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}