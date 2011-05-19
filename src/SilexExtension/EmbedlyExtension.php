<?php

namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use Embedly\Embedly as Embedly,
    Embedly\Extension\Twig\EmbedlyExtension as TwigEmbedlyExtension;

class EmbedlyExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app['embedly'] = $app->share(function () use ($app) {
            $options = isset($app['embedly.options']) ? $app['embedly.options'] : array();
            return new Embedly($options);
        });  
        
        // autoloading the predis library
        if (isset($app['embedly.class_path'])) {
            $app['autoloader']->registerNamespace('Embedly', $app['embedly.class_path']);
        }  
        
        if (isset($app['twig'])) {
            $app['twig']->addExtension(new TwigEmbedlyExtension($app['embedly']));
        }
    }
}
