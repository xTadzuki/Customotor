<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/models/service.php';

class ServiceController extends Controller
{
    public function index(): void
    {
        
        $servicesByCategory = [];
        if (method_exists('Service', 'groupedByCategory')) {
            $servicesByCategory = Service::groupedByCategory();
        }

        
        $services = [];
        if (empty($servicesByCategory)) {
            $services = [
                [
                    'name'        => 'reprogrammation moteur',
                    'category_name' => 'moteur',
                    'price_from'  => 0,
                    'description' => "Optimisation des cartographies pour améliorer couple et puissance, avec un réglage adapté à votre véhicule et à votre usage.",
                ],
                [
                    'name'        => 'optimisation moteur',
                    'category_name' => 'performance',
                    'price_from'  => 0,
                    'description' => "Amélioration de la réponse à l’accélération et du comportement moteur, tout en conservant une approche fiable et cohérente.",
                ],
                [
                    'name'        => 'préparation performance',
                    'category_name' => 'préparation',
                    'price_from'  => 0,
                    'description' => "Préparation personnalisée selon vos objectifs (route / circuit) : configuration, réglages, tests et conseils.",
                ],
            ];
        }

        $this->view('services/index', [
            'title'              => 'customotor - performance',
            'servicesByCategory' => $servicesByCategory,
            'services'           => $services,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo '404';
            return;
        }

        if (!method_exists('Service', 'find')) {
            http_response_code(404);
            echo '404 - service introuvable';
            return;
        }

        $service = Service::find($id);
        if (!$service) {
            http_response_code(404);
            echo '404 - service introuvable';
            return;
        }

        $this->view('services/show', [
            'title'   => 'customotor - service',
            'service' => $service,
        ]);
    }
}