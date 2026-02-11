<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/models/review.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $reviews = [];

        if (method_exists('Review', 'latestApproved')) {
            $reviews = Review::latestApproved(3);
        }

        $this->view('home/index', [
            'title'   => 'customotor - performance garage',
            'reviews' => $reviews,
        ]);
    }
}