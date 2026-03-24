<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('testdb', 'TestDB::index');

$routes->group('tags', function ($routes) {
    $routes->get('/', 'TagsController::index');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    $routes->get('verify/(:num)', 'TagsController::verify/$1');
    $routes->post('renew/confirm', 'TagsController::renewConfirm');
    $routes->match(['get', 'post'], 'create', 'TagsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'TagsController::update/$1');
    $routes->match(['get', 'post'], 'renew', 'TagsController::renew');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});

$routes->group('properties', function ($routes) {
    $routes->get('/', 'PropertiesController::index');
    $routes->match(['get', 'post'], 'create', 'PropertiesController::create');
    // $routes->match(['get', 'post'], 'update/(:num)', 'PropertiesController::update/$1');
    // $routes->post('delete/(:num)', 'PropertiesController::delete/$1');
});

$routes->group('vendors', function ($routes) {
    $routes->get('/', 'VendorsController::index');
    $routes->get('view/(:num)', 'VendorsController::view/$1');
    $routes->match(['get', 'post'], 'create', 'VendorsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'VendorsController::update/$1');
    // $routes->post('delete/(:num)', 'VendorsController::delete/$1');
});

$routes->group('jira', function ($routes) {
    $routes->match(['get', 'post'], 'create', 'JiraController::create');
    $routes->get('download', 'JiraController::download');
});
