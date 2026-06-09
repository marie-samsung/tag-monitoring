<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->addRedirect('/', 'dashboard');
$routes->get('dashboard', 'DashboardController::index');
$routes->get('dashboard/activity-tags', 'DashboardController::getActivityTags');
$routes->get('testdb', 'TestDB::index');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('tags', function ($routes) {
    $routes->get('/', 'TagsController::index');
    $routes->get('export', 'TagsController::exportAsCSV');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    $routes->get('verify/(:num)', 'TagsController::verify/$1');
    $routes->post('renew/confirm', 'TagsController::renewConfirm');
    $routes->post('renew/updateAL', 'TagsController::updateAL');
    $routes->post('update-rule/confirm', 'TagsController::updateRuleRequestConfirm');
    $routes->match(['get', 'post'], 'create', 'TagsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'TagsController::update/$1');
    $routes->match(['get', 'post'], 'renew', 'TagsController::renew');
    $routes->match(['get', 'post'], 'update-rule-request', 'TagsController::createUpdateRuleRequest');
    $routes->get('upload-renewal-justifications', 'TagsController::uploadRenewalJustifications');
    $routes->post('parse-renewal-justifications', 'TagsController::parseRenewalJustifications');
    $routes->post('confirm-renewal-justifications', 'TagsController::confirmRenewalJustifications');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});

$routes->group('tag-requests', function ($routes) {
    $routes->get('/', 'TagRequestsController::index');
    $routes->get('export', 'TagRequestsController::exportAsCSV');
    $routes->get('upload-excel', 'TagRequestsController::uploadExcel');
    $routes->post('parse-excel', 'TagRequestsController::parseExcel');
    $routes->post('prefill', 'TagRequestsController::prefill');
    $routes->get('view/(:num)', 'TagRequestsController::view/$1');
    $routes->get('request-approved/(:num)', 'TagRequestsController::requestApproved/$1');
    $routes->post('add-new-tag', 'TagRequestsController::addNewTag');
    $routes->post('add-new-rule', 'TagRequestsController::addNewRule');
    $routes->get('new-rule-request/(:num)', 'TagRequestsController::newRuleRequest/$1');
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

$routes->group('activity-logs', function ($routes) {
    $routes->get('/', 'ActivityLogsController::index');
    $routes->get('export', 'ActivityLogsController::exportAsCSV');
    $routes->get('view/(:num)', 'ActivityLogsController::view/$1');
    $routes->post('comment', 'ActivityLogsController::comment');
    $routes->post('upload', 'ActivityLogsController::uploadFile');
    $routes->match(['get', 'post'], 'create', 'ActivityLogsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'ActivityLogsController::update/$1');
    $routes->post('update-status/(:num)', 'ActivityLogsController::updateStatus/$1');
    $routes->post('update-assignee/(:num)', 'ActivityLogsController::updateAssignee/$1');
    $routes->post('update-priority/(:num)', 'ActivityLogsController::updatePriority/$1');
});
