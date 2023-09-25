<?php

use App\Controllers\MonitoringController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'LoginController::index');
$routes->get('/lupa_password', 'LoginController::forgotPassword');
$routes->post('/login', 'LoginController::login');
$routes->get('/beranda', 'Beranda::index');
$routes->get('/uploadData/angkaPDRB', 'DataUploadController::viewUploadAngkaPDRB');
$routes->get('/admin/administrator', 'AdminController::viewAdministrator');
$routes->get('/admin/roleAndPermission', 'AdminController::viewRoleAndPermission');
$routes->get('/admin/createUserForm', 'AdminController::viewCreateUserForm');
$routes->get('/tabelPDRB/tabelRingkasan', 'TabelPDRBController::viewTabelRingkasan');
$routes->get('/tabelPDRB/tabelPerKota', 'TabelPDRBController::viewTabelPerKota');
$routes->get('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->post('/tabelPDRB/tabelHistoryPutaran/getData', 'TabelPDRBController::getData');
$routes->get('/arahRevisi', 'ArahRevisiController::index');
$routes->get('/monitoring', 'MonitoringController::index');

// routes ekspor
$routes->get('/tabelPDRB/exportExcel', 'TabelPDRBController::exportExcel');
$routes->resource('dataPDRB');

$routes->get('/tabelPDRB/exportPDF', 'TabelPDRBController::exportPDF');
$routes->resource('dataPDRB');
