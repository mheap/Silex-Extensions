<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Predis\Client, 
    Predis\ClientOptions,
    Predis\DispatcherLoop,
    Predis\ConnectionParameters;

class PredisExtension implements ServiceProviderInterface
{
    public function register(Application $app)
    {  
        $app['predis'] = $app->share(function () use ($app) {
            $server = isset($app['predis.server']) ? $app['predis.server'] : array();
            $config = isset($app['predis.config']) ? $app['predis.config'] : array();
        
            return new Client(new ConnectionParameters($server), new ClientOptions($config));
        });
        
        // autoloading the predis library
        if (isset($app['predis.class_path'])) {
            $app['autoloader']->registerNamespace('Predis', $app['predis.class_path']);
        }
    }
}
