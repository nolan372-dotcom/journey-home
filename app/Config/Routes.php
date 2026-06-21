<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Placements
$routes->get('placements/new',         'Placements::new');
$routes->post('placements',            'Placements::create');
$routes->post('placements/(:num)/end', 'Placements::end/$1');

// Animals
$routes->get('animals',                    'Animals::index');
$routes->get('animals/new',                'Animals::new');
$routes->post('animals',                   'Animals::create');
$routes->get('animals/(:num)',             'Animals::show/$1');
$routes->get('animals/(:num)/edit',        'Animals::edit/$1');
$routes->post('animals/(:num)',            'Animals::update/$1');
$routes->post('animals/(:num)/archive',    'Animals::archive/$1');

// Users
$routes->get('users',                        'Users::index');
$routes->get('users/new',                    'Users::new');
$routes->post('users',                       'Users::create');
$routes->post('users/(:num)/toggle-active',  'Users::toggleActive/$1');

// Foster homes
$routes->get('fosters',              'Fosters::index');
$routes->get('fosters/new',          'Fosters::new');
$routes->post('fosters',             'Fosters::create');
$routes->get('fosters/(:num)',       'Fosters::show/$1');
$routes->get('fosters/(:num)/edit',  'Fosters::edit/$1');
$routes->post('fosters/(:num)',      'Fosters::update/$1');
$routes->post('fosters/(:num)/status', 'Fosters::updateStatus/$1');

service('auth')->routes($routes);
