<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/testdb', 'TestDB::index');
$routes->get('/tags', 'TagsController::index');
