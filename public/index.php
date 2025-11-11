<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Detectar la raíz del proyecto: si el index está en /public (local) o en htdocs (InfinityFree)
$root = __DIR__;
$projectRoot = is_file($root . '/../vendor/autoload.php') ? dirname($root) : $root;

// Modo mantenimiento (compatible con ambos layouts)
$maintenance = $projectRoot . '/storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    require $maintenance;
}

// Autoloader de Composer (compatible con ambos layouts)
require $projectRoot . '/vendor/autoload.php';

// Bootstrap de la app (compatible con ambos layouts)
/** @var \Illuminate\Foundation\Application $app */
$app = require_once $projectRoot . '/bootstrap/app.php';

// Kernel HTTP y manejo de la request (estándar Laravel)
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
