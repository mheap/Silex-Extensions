<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use Predis\Client,
    Predis\DispatcherLoop;

class PredisExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app['predis'] = $app->share(function () use ($app) {
            $config = isset($app['predis.configuration']) ? $app['predis.configuration'] : array();
            $server = isset($config['server']) ? $config['server'] : array();
            
            $client     = new Client($server);
            // $dispatcher = new DispatcherLoop($client);
            
            if(isset($config['dispatcher']) && is_callable($config['dispatcher'])) {
                // call_user_func_array($config['dispatcher'], array($dispatcher));
            }
        
            return $client;
        });
        
        
        // autoloading the doctrine mongodb library
        if (isset($app['predis.class_path'])) {
            $app['autoloader']->registerNamespace('Predis', $app['predis.class_path']);
        }
    }
}
