<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/models/contactrequest.php';
require_once APP_ROOT . '/app/helpers/mailer.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class ContactController extends Controller
{
    public function index(): void
    {
        $success = (isset($_GET['sent']) && $_GET['sent'] === '1');

        $this->view('contact/index', [
            'title'   => 'customotor - contact',
            'errors'  => [],
            'old'     => [],
            'success' => $success,
        ]);
    }

    public function store(): void
    {
        // CSRF d'abord
        csrf_verify();

        $data = [
            'firstname' => trim((string)($_POST['firstname'] ?? '')),
            'lastname'  => trim((string)($_POST['lastname'] ?? '')),
            'email'     => trim((string)($_POST['email'] ?? '')),
            'phone'     => trim((string)($_POST['phone'] ?? '')),
            'brand'     => trim((string)($_POST['brand'] ?? '')),
            'model'     => trim((string)($_POST['model'] ?? '')),
            'year'      => trim((string)($_POST['year'] ?? '')),
            'message'   => trim((string)($_POST['message'] ?? '')),
        ];

        $errors = [];

        if ($data['firstname'] === '') $errors['firstname'] = 'prénom requis';
        if ($data['lastname'] === '')  $errors['lastname']  = 'nom requis';
        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email invalide';
        }

        if ($data['message'] === '') {
            $errors['message'] = 'message requis';
        } elseif (mb_strlen($data['message']) < 10) {
            $errors['message'] = '10 caractères minimum';
        }

        // année optionnelle
        if ($data['year'] !== '') {
            $maxYear = (int)date('Y') + 1;
            if (!ctype_digit($data['year']) || (int)$data['year'] < 1950 || (int)$data['year'] > $maxYear) {
                $errors['year'] = 'année invalide';
            }
        }

        if ($errors) {
            $this->view('contact/index', [
                'title'   => 'customotor - contact',
                'errors'  => $errors,
                'old'     => $data,
                'success' => false,
            ]);
            return;
        }

        $user = auth_user();

        // normalisation année : '' => null, sinon int
        $year = null;
        if ($data['year'] !== '') {
            $year = (int)$data['year'];
        }

        // insertion BDD
        ContactRequest::create([
            'user_id'   => isset($user['id']) ? (int)$user['id'] : null,
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] !== '' ? $data['phone'] : null,
            'brand'     => $data['brand'] !== '' ? $data['brand'] : null,
            'model'     => $data['model'] !== '' ? $data['model'] : null,
            'year'      => $year,
            'message'   => $data['message'],
        ]);

        // emails 
        $adminEmail = $GLOBALS['config']['mail']['admin_email'] ?? 'admin@customotor.local';

        send_mail(
            $data['email'],
            'customotor - demande reçue',
            "Bonjour {$data['firstname']},\n\nVotre demande a bien été reçue.\nNous vous répondrons sous 24 à 48h.\n\ncustomotor"
        );

        send_mail(
            (string)$adminEmail,
            'nouvelle demande contact',
            "Nouvelle demande reçue :\n\n{$data['firstname']} {$data['lastname']}\n{$data['email']}\n\nMessage:\n{$data['message']}"
        );

        // PRG
        header('Location: ' . BASE_URL . '/contact?sent=1');
        exit;
    }
}