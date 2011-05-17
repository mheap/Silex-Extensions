<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MandangoExtension(), array(
    'mandango.token' => '123456'    
));


$app->get('/', function() use($app) {
    return 'Silex';
});

$app->run();