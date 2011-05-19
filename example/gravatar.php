<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.class_path' => __DIR__ . '/../vendor/twig/lib',
    'twig.path'       => __DIR__ . '/twig'
));
        
$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\GravatarExtension(), array(
    'gravatar.class_path' => __DIR__ . '/../vendor/gravatar-php/src',
    'gravatar.cache_dir'  => sys_get_temp_dir() . '/gravatar',
    'gravatar.cache_ttl'  => 500,
    'gravatar.options' => array(
        'size' => 100    
    )    
));

$app->get('/', function() use($app) {
    return $app['twig']->render('gravatar.twig');
});

$app->run();