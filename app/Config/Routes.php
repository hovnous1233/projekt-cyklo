<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainPage::index');
$routes->get("zavody/(:any)", "MainPage::zavody/$1");
$routes->get("rocniky/(:any)", "Rocniky::index/$1");

// Akce pro formuláře (přidat, upravit, smazat)
$routes->post('rocniky/add', 'FormularPridat::index');
$routes->get('rocniky/edit-data/(:num)', 'FormularUpravit::getData/$1');
$routes->post('rocniky/edit', 'FormularUpravit::index');
$routes->get('rocniky/delete/(:num)', 'FormularSmazat::index/$1');