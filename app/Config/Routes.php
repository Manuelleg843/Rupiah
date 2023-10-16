<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// otorisasi
$routes->get('/', 'LoginController::index');
$routes->get('/lupa_password', 'LoginController::forgotPassword');
$routes->get('/login', 'LoginController::index');
$routes->post('/UserController/login', 'UserController::index');
$routes->get('/UserController/logout', 'UserController::logout');

// halaman beranda
$routes->get('/beranda', 'Beranda::index');
$routes->get('/beranda/ShowBarChart', 'Beranda::BarChart');
$routes->post('/beranda/ShowLineChart', 'Beranda::getData');

// admin
$routes->get('/admin/administrator', 'AdminController::viewAdministrator');
$routes->post('/admin/administrator', 'AdminController::viewAdministrator');
$routes->delete('/admin/administrator/(:num)', 'AdminController::deleteUser/$1');
$routes->post('/admin/administrator/update/(:num)', 'AdminController::updateUser/$1');
$routes->get('/admin/roleAndPermission', 'AdminController::viewRoleAndPermission');
$routes->post('/admin/roleAndPermission/update/(:num)', 'AdminController::updatePermission/$1');
$routes->get('/admin/createUserForm', 'AdminController::viewCreateUserForm');

// halaman monitoring
$routes->get('/monitoring', 'MonitoringController::index');
$routes->get('/monitoring/updateStatus', 'MonitoringController::updateStatus');

// halaman upload
$routes->post('/downloadExcel', 'DownloadExcelController::download');
$routes->post('/uploadExcel', 'UploadExcelController::upload');
$routes->get('/uploadData/angkaPDRB', 'DataUploadController::index');
$routes->post('/uploadData/angkaPDRB/getData', 'DataUploadController::getData');

// halaman tabel ringkasan 
$routes->get('/tabelPDRB/tabelRingkasan', 'TabelRingkasanController::index');
$routes->post('/tabelPDRB/tabelRingkasan/getData', 'TabelRingkasanController::getData');

// halaman tabel per kota
$routes->get('/tabelPDRB/tabelPerKota', 'TabelPDRBController::viewTabelPerKota');
$routes->post('/tabelPDRB/getDataPerKota', 'TabelPDRBController::getDataTabelPerKota');

// halaman tabel history putaran
$routes->get('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->post('/tabelPDRB/tabelHistoryPutaran/getDataHistory', 'TabelPDRBController::getDataHistory');
$routes->post('/tabelPDRB/tabelHistoryPutaran/getPutaranPeriode/(:any)', 'TabelPDRBController::getPutaranPeriode/$1');

// halaman tabel arah revisi
$routes->get('/arahRevisi', 'ArahRevisiController::index');
$routes->post('/arahRevisi/getData', 'ArahRevisiController::getData');

// routes ekpor excel 
$routes->post('/tabelPDRB/tabelRingkasan/exportExcel', 'TabelRingkasanController::exportExcel');
$routes->get('/tabelPDRB/tabelRingkasan/exportExcel/(:any)/(:any)/(:any)', 'TabelRingkasanController::exportExcel/$1/$2/$3'); // ekspor halaman ringkasan 
$routes->get('/tabelPDRB/tabelHistoryPutaran/exportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportExcelHistory/$1/$2/$3/$4/$5');  // ekspor halaman history

// routes ekspor
// $routes->get('/tabelPDRB/exportExcel/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportExcel/$1/$2/$3/$4/$5');
// $routes->get('/tabelPDRB/excelAllPutaran/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportExcel/$1/$2/$3/$4/$5');
// $routes->get('/tabelPDRB/exportPDF/(:any)/(:any)/(:any)/(:any)/(:any)', 'TabelPDRBController::exportPDF/$1/$2/$3/$4/$5');
