<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/models/database.php';
require_once APP_ROOT . '/app/models/service.php';


class DashboardController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();

        
        $pdo = Database::getConnection();

        $stats = [
            'users'                => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'requests_total'       => (int)$pdo->query('SELECT COUNT(*) FROM contact_requests')->fetchColumn(),
            'requests_new'         => (int)$pdo->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'new'")->fetchColumn(),
            'appointments_total'   => (int)$pdo->query('SELECT COUNT(*) FROM appointments')->fetchColumn(),
            'appointments_pending' => (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn(),
            'reviews_total'        => (int)$pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn(),
            'reviews_pending'      => (int)$pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'")->fetchColumn(),
            'projects_total'       => (int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
        ];

        $stats['services_total'] = Service::countActive();

        $latestRequests = $pdo->query("
            SELECT id, firstname, lastname, email, status, created_at
            FROM contact_requests
            ORDER BY id DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        $latestAppointments = $pdo->query("
            SELECT a.id, a.requested_at, a.status, u.firstname, u.lastname
            FROM appointments a
            JOIN users u ON u.id = a.user_id
            ORDER BY a.id DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        $latestReviews = $pdo->query("
            SELECT r.id, r.rating, r.status, r.created_at, u.firstname, u.lastname
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            ORDER BY r.id DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/dashboard/index', [
            'title'              => 'admin - dashboard',
            'stats'              => $stats,
            'latestRequests'     => $latestRequests,
            'latestAppointments' => $latestAppointments,
            'latestReviews'      => $latestReviews,
        ]);
        
    }
}