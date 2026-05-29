<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainPage::index');
$routes->get("zavody/(:any)", "MainPage::zavody/$1");

// POST trasa pro zpracování formuláře (přidání i úprava)
$routes->post('rocniky/save', 'FormulareRaceYear::save');

// AJAX trasa pro načtení dat jednoho ročníku
$routes->get('rocniky/edit-data/(:num)', 'FormulareRaceYear::getData/$1');

// Trasa pro smazání ročníku (směruje do hlavního kontroleru Rocniky)
$routes->get('rocniky/delete/(:num)/(:num)', 'Rocniky::delete/$1/$2');

// Hlavní zobrazení tabulky – MUSÍ BÝT POSLEDNÍ
$routes->get("rocniky/(:any)", "Rocniky::index/$1");