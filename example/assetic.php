<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.class_path' => __DIR__ . '/../vendor/twig/lib',
    'twig.path'       => __DIR__ . '/twig'
));
    
$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\AsseticExtension(), array(
    'assetic.class_path' => __DIR__.'/../vendor/assetic/src',
    'assetic.path_to_web' => __DIR__ . '/assetic/output',
    'assetic.options' => array(
        'formulae_cache_dir' => __DIR__ . '/assetic/cache',
        'debug' => false
    ),
    'assetic.filters' => $app->protect(function($fm) {
        $fm->set('yui_css', new Assetic\Filter\Yui\CssCompressorFilter(
            '/usr/share/yui-compressor/yui-compressor.jar'
        ));
        $fm->set('yui_js', new Assetic\Filter\Yui\JsCompressorFilter(
            '/usr/share/yui-compressor/yui-compressor.jar'
        ));
    }),   
    'assetic.assets' => $app->protect(function($am, $fm) {
        
        $am->set('styles', new Assetic\Asset\AssetCache(
            new Assetic\Asset\GlobAsset(
                __DIR__ . '/assetic/resources/css/*.css', 
                array($fm->get('yui_css'))
            ),
            new Assetic\Cache\FilesystemCache(__DIR__ . '/assetic/cache')
        ));
        $am->get('styles')->setTargetPath('css/styles');
    })
));

$app->get('/', function () use($app) {
    return "Hello!";
});

$app->run();