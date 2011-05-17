<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MandangoExtension(), array(
    'mandango.class_path' => __DIR__ . '/../vendor/mandango/src',
    'mandango.token' => '123456',
    
    'mandango.connection' => array(
        'host'      => 'mongodb://localhost:27017',
        'database'  => 'mandango'
    ),
    
    'mandango.configuration' => array(
        'cache_dir'                 => __DIR__ . '/cache/mandango',
        'model_file'                => __DIR__ . '/mandango/models.php',
        'metadata_factory_class'    => 'Model\Mapping\Metadata',
        'metadata_factory_output'   => __DIR__ . '/mandango/Model/Mapping',
        'default_output'            => __DIR__ . '/mandango/Model'
    )
));


$app->get('/', function() use($app) {
    
    $article = $app['mandango']->create('Model\Article');
    $article->setTitle('Test article - ' . time());
    $article->setContent('Lorem Ipsum!');
    
    $app['mandango']->persist($article);
    $app['mandango']->flush();
    
    $amount = $app['mandango']->getRepository('Model\Article')->count();
    
    return 'Articles: ' . $amount;
});

$app->run();