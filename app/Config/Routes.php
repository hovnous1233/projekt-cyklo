<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainPage::index');
$routes->get("zavody/(:any)", "MainPage::zavody/$1");
$routes->get("rocniky/(:any)", "Rocniky::index/$1");