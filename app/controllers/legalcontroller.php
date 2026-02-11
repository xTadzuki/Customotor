<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/core/controller.php';

class LegalController extends Controller
{
    public function mentionsLegales(): void
    {
        $this->view('legal/mentions-legales', [
            'title' => 'customotor - mentions légales',
        ]);
    }

    public function cgv(): void
    {
        $this->view('legal/cgv', [
            'title' => 'customotor - conditions générales de vente',
        ]);
    }

    public function confidentialite(): void
    {
        $this->view('legal/confidentialite', [
            'title' => 'customotor - politique de confidentialité',
        ]);
    }
}