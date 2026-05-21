<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainPage::index');
$routes->get("zavody/(:any)", "MainPage::zavody/$1");

// OPRAVENO: Správný název kontroleru FormulareRaceYear
$routes->post('rocniky/save', 'FormulareRaceYear::save');
$routes->get('rocniky/edit-data/(:num)', 'FormulareRaceYear::getData/$1');
$routes->get('rocniky/delete/(:num)/(:num)', 'FormulareRaceYear::delete/$1/$2');

$routes->get("rocniky/(:any)", "Rocniky::index/$1");