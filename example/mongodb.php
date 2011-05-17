<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

class MyListener {
    function postConnect() { 
        print_r(func_get_args()); 
    }
}

$app = new Silex\Application();

$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MongoDbExtension(), array(
    'mongodb.class_path'    => __DIR__ . '/../vendor/mongodb/lib',
    'mongodb.connection'    => array(
        'configuration' => function($configuration) {
            $configuration->setLoggerCallable(function($logs) {
                print_r($logs);
            });    
        },
        'eventmanager' => function($eventmanager) {
            $eventmanager->addEventListener('postConnect', new MyListener());
        }
    )
));

$app->get('/', function() use($app) {
    $dbs = $app['mongodb']->listDatabases();
    return 'You have ' . count($dbs) . ' Databases';
});

$app->run();