<?php

namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Embedly\Embedly,
    Embedly\Cache\FilesystemCache,
    Embedly\Cache\ExpiringCache,
    Embedly\Extension\Twig\EmbedlyExtension as TwigEmbedlyExtension;

class EmbedlyExtension implements ServiceProviderInterface
{
    public function register(Application $app)
    {  
        $app['embedly'] = $app->share(function () use ($app) {
            $options = isset($app['embedly.options']) ? $app['embedly.options'] : array();
            return new Embedly($options, $app['embedly.cache']);
        });  
        
        $app['embedly.cache'] = $app->share(function () use ($app) {
            $cache = null;
            if(isset($app['embedly.cache_dir'])) {
                $ttl   = isset($app['embedly.cache_ttl']) ? $app['embedly.cache_ttl'] : 360;
                $file  = new FilesystemCache($app['embedly.cache_dir']);
                $cache = new ExpiringCache($file, $ttl);
            }
            return $cache;
        }); 
        
        // autoloading the predis library
        if (isset($app['embedly.class_path'])) {
            $app['autoloader']->registerNamespace('Embedly', $app['embedly.class_path']);
        }  
        
        // enable twig extension
        if (isset($app['twig'])) {
            $app['twig']->addExtension(new TwigEmbedlyExtension($app['embedly']));
        }
    }
}
