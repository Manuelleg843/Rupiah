<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'Beranda::index');
$routes->get('/uploadData/angkaPDRB', 'DataUploadController::viewUploadAngkaPDRB');
$routes->get('/tabelPDRB/tabelRingkasan', 'TabelPDRBController::viewTabelRingkasan');
$routes->get('/tabelPDRB/tabelPerProvinsi', 'TabelPDRBController::viewTabelPerProvinsi');
$routes->get('/tabelPDRB/tabelHistoryPutaran', 'TabelPDRBController::viewTabelHistoryPutaran');
$routes->get('/arahRevisi', 'ArahRevisiController::index');
