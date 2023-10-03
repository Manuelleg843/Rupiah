<?php

use App\Controllers\MonitoringController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'LoginController::index');
$routes->get('/lupa_password', 'LoginController::forgotPassword');
$routes->get('/login', 'LoginController::index');
$routes->post('/UserController/login', 'UserController::index');
$routes->get('/UserController/logout', 'UserController::logout');
$routes->post('/downloadExcel', 'DownloadExcelController::download');
$routes->post('/uploadExcel', 'UploadExcelController::upload');
$routes->get('/beranda', 'Beranda::index');
$routes->get('/uploadData/angkaPDRB', 'DataUploadController::viewUploadAngkaPDRB');
$routes->get('/admin/administrator', 'AdminController::viewAdministrator');
$routes->post('/admin/administrator', 'AdminController::viewAdministrator');
$routes->delete('/admin/administrator/(:num)', 'AdminController::deleteUser/$1');
$routes->post('/admin/administrator/update/(:num)', 'AdminController::updateUser/$1');
$routes->get('/admin/roleAndPermission', 'AdminController::viewRoleAndPermission');
$routes->post('/admin/roleAndPermission/update/(:num)', 'AdminController::updatePermission/$1');
$routes->get('/admin/createUserForm', 'AdminController::viewCreateUserForm');
$routes->get('/tabelPDRB/tabelRingkasan', 'TabelPDRBController::viewTabelRingkasan');
$routes->get('/tabelPDRB/tabelPerKota', 'TabelPDRBController::viewTabelPerKota');
$routes->get('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->post('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->get('/arahRevisi', 'ArahRevisiController::index');
$routes->get('/monitoring', 'MonitoringController::index');

// routes ekspor
$routes->get('/tabelPDRB/exportExcel', 'TabelPDRBController::exportExcel');
$routes->resource('dataPDRB');

$routes->get('/tabelPDRB/exportPDF', 'TabelPDRBController::exportPDF');
$routes->resource('dataPDRB');
