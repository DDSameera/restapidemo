<?php

namespace App\Routes;

use App\Controller\UserController;
use App\Controller\CatalogController;
use Klein\Klein;

$klein = new Klein();

//User
$klein->respond('POST', '/user/register', [new UserController(), 'register']);
$klein->respond('POST', '/user/login', [new UserController(), 'login']);

//Catalog
$klein->respond('POST', '/catalog/create', [new CatalogController(), 'create']);
$klein->respond('PUT', '/catalog/update/[:id]', [new CatalogController(), 'update']);
$klein->respond('DELETE', '/catalog/delete/[:id]', [new CatalogController(), 'delete']);
$klein->respond('GET', '/catalog/id/[:id]', [new CatalogController(), 'getCatById']);

$klein->dispatch();