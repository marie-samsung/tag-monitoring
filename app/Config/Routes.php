<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->addRedirect('/', 'dashboard');
$routes->get('dashboard', 'DashboardController::index');
$routes->get('dashboard/task-tags', 'DashboardController::getTaskTags');
$routes->get('testdb', 'TestDB::index');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('notifications', function ($routes) {
    $routes->get('unread', 'NotificationsController::getUnread');
    $routes->post('mark-read/(:num)', 'NotificationsController::markAsRead/$1');
});

$routes->group('tags', function ($routes) {
    $routes->get('/', 'TagsController::index');
    $routes->get('repository', 'TagsController::repository');
    $routes->get('export', 'TagsController::exportAsExcel');
    $routes->get('view/(:num)', 'TagsController::view/$1');
    $routes->get('verify/(:num)', 'TagsController::verify/$1');
    $routes->match(['get', 'post'], 'verify-multiple', 'TagsController::verifyMultiple');
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
    $routes->get('renewal-justifications', 'TagsController::renewalJustifications');
    $routes->match(['get', 'post'], 'renewal-justifications/update/(:num)', 'TagsController::updateRenewalJustification/$1');
    $routes->post('save-js-code/(:num)', 'TagsController::saveJsCode/$1');
    // $routes->post('delete/(:num)', 'TagsController::delete/$1');
});

$routes->group('tag-requests', function ($routes) {
    $routes->get('/', 'TagRequestsController::index');
    $routes->get('export', 'TagRequestsController::exportAsExcel');
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
    $routes->post('update-property/(:num)', 'TagRequestsController::updateProperty/$1');
    $routes->post('update-status/(:num)', 'TagRequestsController::updateStatus/$1');
    $routes->post('update-type/(:num)', 'TagRequestsController::updateType/$1');
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
    $routes->get('export/(:num)', 'VendorsController::exportAsExcel/$1');
    $routes->match(['get', 'post'], 'create', 'VendorsController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'VendorsController::update/$1');
    // $routes->post('delete/(:num)', 'VendorsController::delete/$1');
});

$routes->group('jira', function ($routes) {
    $routes->get('/', 'JiraController::index');
    $routes->get('export', 'JiraController::exportAsExcel');
    $routes->get('download', 'JiraController::download');
    $routes->match(['get', 'post'], 'add-entry', 'JiraController::addEntry');
    $routes->match(['get', 'post'], 'create', 'JiraController::create');
    $routes->get('update-tags/(:num)', 'JiraController::updateTags/$1');
    $routes->post('update-tags/(:num)', 'JiraController::updateTagsConfirm/$1');
});

$routes->group('tasks', function ($routes) {
    $routes->get('/', 'TasksController::index');
    $routes->get('project/(:any)', 'TasksController::projectView/$1');
    $routes->get('export', 'TasksController::exportAsExcel');
    $routes->get('view/(:num)', 'TasksController::view/$1');
    $routes->post('comment', 'TasksController::comment');
    $routes->post('upload', 'TasksController::uploadFile');
    $routes->match(['get', 'post'], 'create', 'TasksController::create');
    $routes->match(['get', 'post'], 'update/(:num)', 'TasksController::update/$1');
    $routes->post('update-status/(:num)', 'TasksController::updateStatus/$1');
    $routes->post('update-assignee/(:num)', 'TasksController::updateAssignee/$1');
    $routes->post('update-priority/(:num)', 'TasksController::updatePriority/$1');
    $routes->post('update-ticket-id/(:num)', 'TasksController::updateTicketId/$1');
    $routes->post('remove-tag/(:num)', 'TasksController::removeTag/$1');
    $routes->post('bulk-status', 'TasksController::bulkStatus');
    $routes->post('bulk-project', 'TasksController::bulkProject');
    $routes->post('bulk-assignee', 'TasksController::bulkAssignee');
    $routes->get('project-export/excel/(:any)', 'TasksController::exportProjectExcel/$1');
    $routes->get('project-export/pdf/(:any)', 'TasksController::exportProjectPDF/$1');
});

$routes->group('settings', function ($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->post('user/create', 'SettingsController::createUser');
    $routes->post('user/update/(:num)', 'SettingsController::updateUser/$1');
    $routes->post('user/delete/(:num)', 'SettingsController::deleteUser/$1');
    $routes->post('roles/create', 'SettingsController::createRole');
    $routes->post('roles/update/(:num)', 'SettingsController::updateRole/$1');
    $routes->post('permissions/save', 'SettingsController::savePermissions');
    $routes->post('backup/create', 'SettingsController::createBackup');
    $routes->post('backup/settings', 'SettingsController::saveBackupSettings');
    $routes->get('backup/download/(:any)', 'SettingsController::downloadBackup/$1');
    $routes->post('backup/delete/(:any)', 'SettingsController::deleteBackup/$1');
    $routes->post('backup/restore/(:any)', 'SettingsController::restoreBackup/$1');
});

$routes->group('profile', function ($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->post('update', 'ProfileController::update');
});

