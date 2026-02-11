<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';
require_once APP_ROOT . '/app/core/middleware.php';
require_once APP_ROOT . '/app/helpers/auth.php';
require_once APP_ROOT . '/app/models/contactrequest.php';

class AccountController extends Controller
{
    public function dashboard(): void
    {
        Middleware::requireAuth();

        $this->view('account/dashboard', [
            'title' => 'customotor - compte',
            'user'  => auth_user(),
        ]);
    }

    public function requests(): void
    {
        Middleware::requireAuth();

        $user = auth_user();
        $requests = [];

        // Optionnel : si tu relies les demandes à un user_id
        if (!empty($user['id']) && method_exists('ContactRequest', 'allForUser')) {
            $requests = ContactRequest::allForUser((int)$user['id']);
        }

        $this->view('account/requests', [
            'title'    => 'customotor - mes demandes',
            'requests' => $requests,
        ]);
    }
}