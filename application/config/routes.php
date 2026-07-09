<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Route Configuration
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth';

/*
|--------------------------------------------------------------------------
| 404 Override
|--------------------------------------------------------------------------
*/
$route['404_override'] = '';

/*
|--------------------------------------------------------------------------
| Translate URI dashes
|--------------------------------------------------------------------------
*/
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard';
$route['projects'] = 'projects';
$route['tasks'] = 'tasks';
$route['notifications'] = 'notifications';
$route['users'] = 'users';
$route['roles'] = 'roles';
$route['profile'] = 'profile';
$route['kpi'] = 'KPI/index';
$route['kpi/(.+)'] = 'KPI/$1';
