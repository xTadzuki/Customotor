<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/models/vehicle.php';
require_once APP_ROOT . '/app/helpers/csrf.php';
require_once APP_ROOT . '/app/core/validator.php';

class VehicleController extends Controller
{
    public function index(): void
    {
        Middleware::requireAuth();

        $userId = (int)(auth_user()['id'] ?? 0);
        $vehicles = Vehicle::allForUser($userId);

        $created = (isset($_GET['created']) && $_GET['created'] === '1');

        $this->view('account/vehicles', [
            'title'    => 'customotor - mes véhicules',
            'vehicles' => $vehicles,
            'errors'   => [],
            'old'      => [],
            'created'  => $created,
        ]);
    }

    public function store(): void
    {
        Middleware::requireAuth();
        csrf_verify();

        $userId = (int)(auth_user()['id'] ?? 0);

        [$clean, $errors] = Validator::validate($_POST, [
            'brand' => 'required|min:2|max:60',
            'model' => 'required|min:1|max:60',
            'year'  => 'int|between:1950,' . ((int)date('Y') + 1),
            'plate' => 'max:20',
        ]);

        // year optionnelle -> null si vide
        $year = $clean['year'] ?? null;
        if ($year === '' || $year === null) {
            $year = null;
        }

        // plate optionnelle -> null si vide
        $plate = isset($clean['plate']) ? trim((string)$clean['plate']) : '';
        $plate = $plate !== '' ? $plate : null;

        if (!empty($errors)) {
            $vehicles = Vehicle::allForUser($userId);

            $this->view('account/vehicles', [
                'title'    => 'customotor - mes véhicules',
                'vehicles' => $vehicles,
                'errors'   => $errors,
                'old'      => [
                    'brand' => (string)($clean['brand'] ?? ''),
                    'model' => (string)($clean['model'] ?? ''),
                    'year'  => (string)($_POST['year'] ?? ''),
                    'plate' => (string)($_POST['plate'] ?? ''),
                ],
                'created'  => false,
            ]);
            return;
        }

        Vehicle::create([
            'user_id' => $userId,
            'brand'   => (string)$clean['brand'],
            'model'   => (string)$clean['model'],
            'year'    => $year,
            'plate'   => $plate,
        ]);

        // redirect safe en sous-dossier
        header('Location: ' . BASE_URL . '/account/vehicles?created=1');
        exit;
    }
}