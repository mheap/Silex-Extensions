<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();
        
$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MandangoExtension(), array(
    'mandango.class_path'           => __DIR__ . '/../vendor/mandango/src',
    'mandango.token'                => '4dd399ea814c',
    'mandango.cache_dir'            => __DIR__ . '/odm/cache',
    'mandango.default_connection'   => 'local',
    
    'mandango.connections' => array(
        'local' => array(
            'host'     => 'mongodb://localhost:27017',
            'database' => 'mandango'
        )
    ),
    
    'mandango.configuration' => array(
        'metadata_factory_class'    => 'Model\Mapping\Metadata',
        'metadata_factory_output'   => __DIR__ . '/odm/Model/Mapping',
        'default_output'            => __DIR__ . '/odm/Model',
        'schema_file'               => __DIR__ . '/odm/schema.php'
    )
));


$app->get('/', function() use($app) {
    
    $amount = $app['mandango']
        ->getRepository('Model\Article')
        ->count();
    
    $article = $app['mandango']->create('Model\Article');
    $article->setTitle('Article #' . ($amount+1));
    $article->setContent('Lorem ipsum ...');
    $article->save();
    
    return 'Created Model\Article with ID: ' . $article->getId();
});

$app->run();