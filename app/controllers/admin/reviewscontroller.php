<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/models/review.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class ReviewsController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();

        
        $reviews = Review::allPending();

        $this->view('admin/reviews/index', [
            'title'   => 'admin - avis',
            'reviews' => $reviews,
            'updated' => (isset($_GET['updated']) && $_GET['updated'] === '1'),
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

        $review = Review::findAdmin($id);
        if (!$review) {
            http_response_code(404);
            echo '404';
            return;
        }

        $this->view('admin/reviews/edit', [
            'title'  => 'admin - avis',
            'review' => $review,
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

        // mini whitelist côté controller 
        $allowed = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowed, true)) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        try {
            Review::updateStatus($id, $status);
        } catch (Throwable $e) {
            http_response_code(400);
            echo 'statut invalide';
            return;
        }

        

        header('Location: ' . BASE_URL . '/admin/reviews?updated=1');
        exit;
    }
}