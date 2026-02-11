<?php



/*
|--------------------------------------------------------------------------
| configuration globale de l’application
|--------------------------------------------------------------------------
| ce fichier est chargé au démarrage (index.php)
|--------------------------------------------------------------------------
*/

$env = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'local';
$url = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: 'http://localhost';

return [

    /*
    |--------------------------------------------------------------------------
    | application
    |--------------------------------------------------------------------------
    */
    'app' => [
        'name' => 'customotor',
        'env'  => $env, // local | prod
        'url'  => $url,
    ],

    /*
    |--------------------------------------------------------------------------
    | sécurité
    |--------------------------------------------------------------------------
    */
    'security' => [
        'csrf' => true,
        'session_name' => 'customotor_session',
    ],

    /*
    |--------------------------------------------------------------------------
    | email
    |--------------------------------------------------------------------------
    */
    'mail' => [
        'enabled'    => ($env !== 'local'), // false en local, true en prod
        'from_email' => 'no-reply@customotor.local',
        'from_name'  => 'customotor',
        'admin_email'=> 'admin@customotor.local',
    ],

];