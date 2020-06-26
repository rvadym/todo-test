<?php
require_once '../vendor/autoload.php';
$settings = require_once '../settings.php';

use ToDoTest\Adapters\App;
use ToDoTest\Adapters\Container;

$container = new Container($settings);
$app = new App($container->init());
$app->init()->run();
