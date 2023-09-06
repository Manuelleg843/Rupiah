<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

#------------------------------------#
#        Menu Upload Data            #
#------------------------------------#
$routes->get('/pdrb_upload', 'uploadData::index/uploadAngkaPDRB');



#------------------------------------#
#        Menu Tabel PDRB             #
#------------------------------------#
$routes->get('/tabel_ringkasan', 'tabelPDRB::index/tabelRingkasan');
$routes->get('/tabel_perKota', 'tabelPDRB::index/tabelPerKota');
$routes->get('/tabel_historiPutaran', 'tabelPDRB::index/tabelPutaran');
