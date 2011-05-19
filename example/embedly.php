<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.class_path' => __DIR__ . '/../vendor/twig/lib',
    'twig.path'       => __DIR__ . '/twig'
));
        
$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\EmbedlyExtension(), array(
    'embedly.class_path' => __DIR__ . '/../vendor/embedly-php/src',
    'embedly.cache_dir'  => sys_get_temp_dir() . '/gravatar',
    'embedly.cache_ttl'  => 500,
));

$app->get('/', function() use($app) {
    return $app['twig']->render('embedly.twig', array(
        'video_url' => 'http://www.youtube.com/watch?v=c9BA5e2Of_U'    
    ));
});

$app->run();