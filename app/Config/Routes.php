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

    $routes->get('update/(:num)', 'TagsController::update/$1');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});

$routes->group('jira', function ($routes) {
    $routes->match(['get', 'post'], 'create', 'JiraController::create');
});
