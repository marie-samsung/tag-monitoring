<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('testdb', 'TestDB::index');

$routes->group('tags', function ($routes) {
    $routes->get('/', 'TagsController::index');
    $routes->get('export', 'TagsController::exportAsCSV');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    $routes->get('verify/(:num)', 'TagsController::verify/$1');
    $routes->post('renew/confirm', 'TagsController::renewConfirm');
    $routes->post('renew/updateAL', 'TagsController::updateAL');
    $routes->match(['get', 'post'], 'create', 'TagsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'TagsController::update/$1');
    $routes->match(['get', 'post'], 'renew', 'TagsController::renew');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});

$routes->group('tag-requests', function ($routes) {
    $routes->get('/', 'TagRequestsController::index');
    $routes->get('view/(:num)', 'TagRequestsController::view/$1');
    $routes->post('add-new-tag', 'TagRequestsController::addNewTag');
    $routes->post('add-new-rule', 'TagRequestsController::addNewRule');
    $routes->post('upload', 'TagRequestsController::uploadFile');
    $routes->post('create/confirm', 'TagRequestsController::createConfirm');
    $routes->match(['get', 'post'], 'create', 'TagRequestsController::create');
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
    $routes->get('export/(:num)', 'VendorsController::exportAsCSV/$1');
    $routes->match(['get', 'post'], 'create', 'VendorsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'VendorsController::update/$1');
    // $routes->post('delete/(:num)', 'VendorsController::delete/$1');
});

$routes->group('jira', function ($routes) {
    $routes->get('/', 'JiraController::index');
    $routes->get('export', 'JiraController::exportAsCSV');
    $routes->get('download', 'JiraController::download');
    $routes->match(['get', 'post'], 'add-entry', 'JiraController::addEntry');
    $routes->match(['get', 'post'], 'create', 'JiraController::create');
});

$routes->group('activity-log', function ($routes) {
    $routes->get('/', 'ActivityLogController::index');
});
