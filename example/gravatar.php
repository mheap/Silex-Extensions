<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\GravatarExtension(), array(
    'gravatar.options' => array(
        'size' => 75    
    )    
));

$app->get('/', function() use($app) {
    return $app['gravatar']->exist('sven.eisenschmidt@gmail.com');
});

$app->run();