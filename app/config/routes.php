<?php

/*
|--------------------------------------------------------------------------
| Routes de l’application
|--------------------------------------------------------------------------
| Format :
| [HTTP_METHOD, URI, Controller@action]
|--------------------------------------------------------------------------
*/

return [

    // -----------------------------
    // Pages publiques
    // -----------------------------
    ['GET',  '/',                    'HomeController@index'],

    ['GET',  '/services',            'ServiceController@index'],
    ['GET',  '/services/{id}',       'ServiceController@show'],

    ['GET',  '/lookbook',            'LookbookController@index'],
    ['GET',  '/lookbook/{id}',       'LookbookController@show'],

    ['GET',  '/contact',             'ContactController@index'],
    ['POST', '/contact',             'ContactController@store'],

    ['GET',  '/reviews',             'ReviewController@publicIndex'],

    ['GET',  '/mentions-legales',    'LegalController@mentionsLegales'],
    ['GET',  '/cgv',                 'LegalController@cgv'],
    ['GET',  '/confidentialite',     'LegalController@confidentialite'],

    // -----------------------------
    // Authentification
    // -----------------------------
    ['GET',  '/login',               'AuthController@login'],
    ['POST', '/login',               'AuthController@authenticate'],
    ['GET',  '/register',            'AuthController@register'],
    ['POST', '/register',            'AuthController@store'],
    ['GET',  '/logout',              'AuthController@logout'],

    // -----------------------------
    // Espace client (auth requis)
    // -----------------------------
    ['GET',  '/account',             'AccountController@dashboard'],
    ['GET',  '/account/requests',    'AccountController@requests'],

    ['GET',  '/account/appointments','AppointmentController@index'],
    ['POST', '/account/appointments','AppointmentController@store'],

    ['GET',  '/account/reviews',     'ReviewController@index'],
    ['POST', '/account/reviews',     'ReviewController@store'],

    ['GET',  '/account/vehicles',    'VehicleController@index'],
    ['POST', '/account/vehicles',    'VehicleController@store'],

    // -----------------------------
    // Administration (admin requis)
    // -----------------------------
    ['GET',  '/admin',                       'Admin/DashboardController@index'],

    ['GET',  '/admin/requests',              'Admin/RequestsController@index'],
    ['GET',  '/admin/requests/{id}',         'Admin/RequestsController@show'],
    ['POST', '/admin/requests/{id}',         'Admin/RequestsController@update'],

    ['GET',  '/admin/appointments',          'Admin/AppointmentsController@index'],
    ['GET',  '/admin/appointments/{id}/edit','Admin/AppointmentsController@edit'],
    ['POST', '/admin/appointments/{id}',     'Admin/AppointmentsController@update'],

    ['GET',  '/admin/reviews',               'Admin/ReviewsController@index'],
    ['GET',  '/admin/reviews/{id}/edit',     'Admin/ReviewsController@edit'],
    ['POST', '/admin/reviews/{id}',          'Admin/ReviewsController@update'],

    ['GET',  '/admin/projects',              'Admin/ProjectsController@index'],
    ['GET',  '/admin/projects/create',       'Admin/ProjectsController@create'],
    ['POST', '/admin/projects',              'Admin/ProjectsController@store'],
    ['GET',  '/admin/projects/{id}/edit',    'Admin/ProjectsController@edit'],
    ['POST', '/admin/projects/{id}',         'Admin/ProjectsController@update'],
    ['POST', '/admin/projects/{id}/delete',  'Admin/ProjectsController@delete'],

    ['GET',  '/admin/services',              'Admin/ServicesController@index'],
    ['GET',  '/admin/services/create',       'Admin/ServicesController@create'],
    ['POST', '/admin/services',              'Admin/ServicesController@store'],
    ['GET',  '/admin/services/{id}/edit',    'Admin/ServicesController@edit'],
    ['POST', '/admin/services/{id}',         'Admin/ServicesController@update'],
    ['POST', '/admin/services/{id}/delete',  'Admin/ServicesController@delete'],

    ['POST', '/admin/projects/{id}/images',        'ProjectsController@addImage'],
    ['POST', '/admin/project-images/{id}/delete', 'ProjectsController@deleteImage'],

];