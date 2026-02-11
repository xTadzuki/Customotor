<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/models/review.php';
require_once APP_ROOT . '/app/helpers/csrf.php';

class ReviewController extends Controller
{
    // page publique: avis validés
    public function publicIndex(): void
    {
        $reviews = Review::publicApproved();

        $this->view('reviews/index', [
            'title'   => 'customotor - avis clients',
            'reviews' => $reviews,
        ]);
    }

    // page compte: déposer un avis
    public function index(): void
    {
        Middleware::requireAuth();

        $this->view('account/reviews', [
            'title'  => 'customotor - mes avis',
            'errors' => [],
            'old'    => [],
            'sent'   => (isset($_GET['sent']) && $_GET['sent'] === '1'),
        ]);
    }

    public function store(): void
    {
        Middleware::requireAuth();
        csrf_verify();

        $user = auth_user();

        $rating  = trim((string)($_POST['rating'] ?? ''));
        $comment = trim((string)($_POST['comment'] ?? ''));

        $errors = [];

        if ($rating === '' || !ctype_digit($rating) || (int)$rating < 1 || (int)$rating > 5) {
            $errors['rating'] = 'note entre 1 et 5';
        }

        if ($comment === '' || mb_strlen($comment) < 10) {
            $errors['comment'] = '10 caractères minimum';
        } elseif (mb_strlen($comment) > 800) {
            $errors['comment'] = 'maximum 800 caractères';
        }

        if ($errors) {
            $this->view('account/reviews', [
                'title'  => 'customotor - mes avis',
                'errors' => $errors,
                'old'    => ['rating' => $rating, 'comment' => $comment],
                'sent'   => false,
            ]);
            return;
        }

        Review::create([
            'user_id'  => (int)($user['id'] ?? 0),
            'rating'   => (int)$rating,
            'comment'  => $comment,
        ]);

        header('Location: ' . BASE_URL . '/account/reviews?sent=1');
        exit;
    }
}