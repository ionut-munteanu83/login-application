<?php
use App\Classes\Routing;
require_once '../src/includes/session.php';

define('DS', DIRECTORY_SEPARATOR);
define('APP_DIR', realpath(dirname(dirname(__FILE__))).DS.'src');

require_once '../src/includes/autoload.php';
require_once '../vendor/autoload.php';

$pageAlias = (!$_GET)?$_SERVER['REQUEST_URI']:substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '?'));

$routing = new Routing($pageAlias);
$routing->routeUrl();