<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/models/appointment.php';
require_once APP_ROOT . '/app/helpers/mailer.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class AppointmentController extends Controller
{
    public function index(): void
    {
        Middleware::requireAuth();

        $user = auth_user();
        $appointments = Appointment::allForUser((int)($user['id'] ?? 0));

        $this->view('account/appointments', [
            'title'        => 'customotor - mes rendez-vous',
            'appointments' => $appointments,
            'errors'       => [],
            'old'          => [],
            'success'      => (isset($_GET['created']) && $_GET['created'] === '1'),
        ]);
    }

    public function store(): void
    {
        Middleware::requireAuth();
        csrf_verify();

        $user = auth_user();

        $requested_at = trim((string)($_POST['requested_at'] ?? ''));
        $note         = trim((string)($_POST['note'] ?? ''));

        $errors = [];

        // format HTML datetime-local: "YYYY-MM-DDTHH:MM"
        $dt = null;
        if ($requested_at === '') {
            $errors['requested_at'] = 'date/heure requise';
        } else {
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $requested_at);
            if (!$dt) {
                $errors['requested_at'] = 'format invalide';
            } else {
                $now = new DateTime();
                if ($dt < $now) {
                    $errors['requested_at'] = 'date/heure dans le passé';
                }
            }
        }

        // note optionnelle (limite anti-abus)
        if ($note !== '' && mb_strlen($note) > 800) {
            $errors['note'] = 'maximum 800 caractères';
        }

        if ($errors) {
            $appointments = Appointment::allForUser((int)($user['id'] ?? 0));

            $this->view('account/appointments', [
                'title'        => 'customotor - mes rendez-vous',
                'appointments' => $appointments,
                'errors'       => $errors,
                'old'          => [
                    'requested_at' => $requested_at,
                    'note'         => $note,
                ],
                'success'      => false,
            ]);
            return;
        }

        
        $mysqlDate = $dt->format('Y-m-d H:i:s');

        Appointment::create([
            'user_id'       => (int)($user['id'] ?? 0),
            'requested_at'  => $mysqlDate,
            'note'          => $note,
        ]);

        // notif email 
        $email = (string)($user['email'] ?? '');
        if ($email !== '') {
            $firstname = (string)($user['firstname'] ?? '');
            send_mail(
                $email,
                'customotor - demande de rendez-vous',
                "Bonjour {$firstname},\n\nVotre demande de rendez-vous a bien été envoyée.\nStatut : en attente de confirmation.\n\ncustomotor"
            );
        }

        header('Location: ' . BASE_URL . '/account/appointments?created=1');
        exit;
    }
}