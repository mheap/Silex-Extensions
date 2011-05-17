<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\PredisExtension(), array(
    'predis.class_path'    => __DIR__ . '/../vendor/predis/lib',
    'predis.server'  => array(
        'host' => '127.0.0.1',
        'port' => 6379
    ),
    'predis.config'  => array(
        'prefix' => 'predis__'
    )
));

$app->get('/', function() use($app) {
    $app['predis']->set('test', time());
    return 'Redis: "SET" "test" "' . $app['predis']->get('test') . '"';
});

$app->run();