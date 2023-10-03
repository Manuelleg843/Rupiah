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
$routes->get('/tabelPDRB/tabelPerKota', 'TabelPDRBController::viewTabelPerKota');
$routes->get('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->post('/tabelPDRB/tabelHistoryPutaran/getData', 'TabelPDRBController::getData');
$routes->get('/arahRevisi', 'ArahRevisiController::index');
$routes->get('/monitoring', 'MonitoringController::index');

// halaman tabel ringkasan 
$routes->get('/tabelPDRB/tabelRingkasan', 'TabelRingkasanController::index');
$routes->get('/tabelPDRB/tabelRingkasan/perbandinganPertumbuhanQ-TO-Q', 'TabelRingkasanController::viewPerbandinganPertumbuhanQ');
$routes->get('/tabelPDRB/tabelRingkasan/(:segment)', 'TabelRingkasanController::redirectPage/$1');
$routes->post('/tabelPDRB/tabelRingkasan/getData', 'TabelRingkasanController::getData');

// // tabel ringakasan PageController
// $routes->post('page/redirectToPage', 'PageController::redirectToPage');


// routes ekspor
$routes->get('/tabelPDRB/exportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportExcel/$1/$2/$3/$4/$5');
$routes->get('/tabelPDRB/excelAllPutaran/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportExcel/$1/$2/$3/$4/$5');
$routes->get('/tabelPDRB/exportPDF/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportPDF/$1/$2/$3/$4/$5');
