<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MemcacheExtension(), array(
    'memcache.library'    => 'memcached',
    'memcache.server' => array(
        array('127.0.0.1', 11211)    
    )
));

$app->get('/', function() use($app) {
    $app['memcache']->set('test', time());
    return 'Memcache: "GET" "test" "' . $app['memcache']->get('test') . '"';
});

$app->run();