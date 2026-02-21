<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('testdb', 'TestDB::index');

$routes->group('tags', function ($routes) {
    $routes->get('/', 'TagsController::index');
    $routes->match(['get', 'post'], 'create', 'TagsController::create');

    // $routes->get('edit/(:num)', 'TagsController::edit/$1');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});