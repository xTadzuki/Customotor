<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/models/appointment.php';
require_once APP_ROOT . '/app/helpers/mailer.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class AppointmentsController extends Controller
{
    private const ALLOWED_STATUS = ['pending', 'confirmed', 'cancelled'];

    public function index(): void
    {
        Middleware::requireAdmin();

        $appointments = Appointment::allAdmin();

        $this->view('admin/appointments/index', [
            'title'        => 'admin - rendez-vous',
            'appointments' => $appointments,
            'updated'      => (isset($_GET['updated']) && $_GET['updated'] === '1'),
        ]);
    }

    public function edit(array $params): void
    {
        Middleware::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $appointment = Appointment::findAdmin($id);
        if (!$appointment) {
            http_response_code(404);
            echo '404';
            return;
        }

        $this->view('admin/appointments/edit', [
            'title'       => 'admin - rendez-vous',
            'appointment' => $appointment,
        ]);
    }

    public function update(array $params): void
    {
        Middleware::requireAdmin();
        csrf_verify();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        $status = (string)($_POST['status'] ?? 'pending');
        if (!in_array($status, self::ALLOWED_STATUS, true)) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        try {
            Appointment::updateStatus($id, $status);
        } catch (Throwable $e) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        // notif email client
        $row = Appointment::findAdmin($id);
        if (is_array($row) && !empty($row['email'])) {
            $firstname = (string)($row['firstname'] ?? '');
            send_mail(
                (string)$row['email'],
                'customotor - mise à jour rendez-vous',
                "Bonjour {$firstname},\n\nLe statut de votre rendez-vous est maintenant : {$status}.\n\ncustomotor"
            );
        }

        header('Location: ' . BASE_URL . '/admin/appointments?updated=1');
        exit;
    }
}