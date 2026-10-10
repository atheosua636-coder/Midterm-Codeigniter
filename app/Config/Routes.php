<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

//Public
$routes->get('/', 'AuthController::loginForm');
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::attemptLogin');

//Product Management
$routes->get('products', 'ProductsController::index', ['filter' => 'authentication']);
$routes->get('products/new', 'ProductsController::createForm', ['filter' => 'authentication']);
$routes->post('products', 'ProductsController::create', ['filter' => 'authentication']);
$routes->get('products/edit/(:num)', 'ProductsController::edit/$1', ['filter' => 'authentication']);
$routes->post('products/update/(:num)', 'ProductsController::update/$1', ['filter' => 'authentication']);
$routes->post('products/delete/(:num)', 'ProductsController::delete/$1', ['filter' => 'authentication']);

//Customer Management
$routes->get('customers', 'CustomersController::index', ['filter' => 'authentication']);
$routes->get('customers/new', 'CustomersController::createForm', ['filter' => 'authentication']);
$routes->post('customers', 'CustomersController::create', ['filter' => 'authentication']);
$routes->get('customers/edit/(:num)', 'CustomersController::edit/$1', ['filter' => 'authentication']);
$routes->post('customers/update/(:num)', 'CustomersController::update/$1', ['filter' => 'authentication']);
$routes->post('customers/delete/(:num)', 'CustomersController::delete/$1', ['filter' => 'authentication']);

//Staff Management
$routes->get('staff', 'StaffController::index', ['filter' => 'authentication']);
$routes->get('staff/new', 'StaffController::createForm', ['filter' => 'authentication']);
$routes->post('staff', 'StaffController::create', ['filter' => 'authentication']);
$routes->get('staff/edit/(:num)', 'StaffController::edit/$1', ['filter' => 'authentication']);
$routes->post('staff/update/(:num)', 'StaffController::update/$1', ['filter' => 'authentication']);
$routes->post('staff/delete/(:num)', 'StaffController::delete/$1', ['filter' => 'authentication']);

//Sales
$routes->get('sales/new', 'SalesController::createForm', ['filter' => 'authentication']);
$routes->post('sales', 'SalesController::record', ['filter' => 'authentication']);
$routes->get('sales/history', 'SalesController::history', ['filter' => 'authentication']);

//Logout
$routes->post('logout', 'AuthController::logout', ['filter' => 'authentication']);
