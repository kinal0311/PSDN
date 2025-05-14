<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/*

| -------------------------------------------------------------------------

| URI ROUTING

| -------------------------------------------------------------------------

| This file lets you re-map URI requests to specific controller functions.

|

| Typically there is a one-to-one relationship between a URL string

| and its corresponding controller class/method. The segments in a

| URL normally follow this pattern:

|

|	example.com/class/method/id/

|

| In some instances, however, you may want to remap this relationship

| so that a different class/function is called than the one

| corresponding to the URL.

|

| Please see the user guide for complete details:

|

|	https://codeigniter.com/user_guide/general/routing.html

|

| -------------------------------------------------------------------------

| RESERVED ROUTES

| -------------------------------------------------------------------------

|

| There are three reserved routes:

|

|	$route['default_controller'] = 'welcome';

|

| This route indicates which controller class should be loaded if the

| URI contains no data. In the above example, the "welcome" class

| would be loaded.

|

|	$route['404_override'] = 'errors/page_missing';

|

| This route will tell the Router which controller/method to use if those

| provided in the URL cannot be matched to a valid route.

|

|	$route['translate_uri_dashes'] = FALSE;

|

| This is not exactly a route, but allows you to automatically route

| controller and method names that contain dashes. '-' isn't a valid

| class or method name character, so it requires translation.

| When you set this option to TRUE, it will replace ALL dashes in the

| controller and method URI segments.

|

| Examples:	my-controller/index	-> my_controller/index

|		my-controller/my-method	-> my_controller/my_method

*/

$route['default_controller'] = 'home';

$route['404_override'] = 'error';

$route['translate_uri_dashes'] = FALSE;

$route["(:any)/create_new_entry"]="admin/create_new_entry";
$route["(:any)/create_certificate_entry"]="admin/create_certificate_entry";

$route["(:any)/entry_list"]="admin/entry_list";

$route["(:any)/edit_entry/(:num)"]="admin/edit_entry/$2";

$route["(:any)/dashboard"]="admin/dashboard";

$route["(:any)/downloadpdf/(:any)"]="admin/downloadpdf/$2";

$route["(:any)/rto_dashboard"]="admin/rto_dashboard";

$route["(:any)/dealersalesreport"]="admin/dealersalesreport";

$route["(:any)/view_dealersalesreport"]="admin/view_dealersalesreport";

$route["(:any)/inventoryreport"]="admin/inventoryreport";

$route["(:any)/view_inventoryreport"]="admin/view_inventoryreport";

$route["(:any)/salesreport"]="admin/salesreport";

$route["(:any)/view_salesreport"]="admin/view_salesreport";

// Add on Development

$route["(:any)/create_certificate"]="admin/create_certificate";

$route["(:any)/create_renewal_entry"]="admin/create_renewal_entry";

$route["(:any)/renewal_list"]="admin/renewal_list";

$route['portal/downloadwebpdf'] = 'admin/downloadwebpdf';

//$route["(:any)/edit_entry/(:num)"]="admin/edit_entry/$2";
