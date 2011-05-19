<?php

namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use Knplabs\Bundle\MarkdownBundle\Parser\MarkdownParser;

use SilexExtension\MarkdownExtension\MarkdownTwigExtension;

class MarkdownExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app['markdown'] = $app->share(function () use ($app) {
            $features = isset($app['markdown.features']) ? $app['markdown.features'] : array();
            return new MarkdownParser($features);
        });  
        
        // autoloading the predis library
        if (isset($app['markdown.class_path'])) {
            $app['autoloader']->registerNamespace('Knplabs\\Bundle\\MarkdownBundle', $app['markdown.class_path']);
        }  
        
        if (isset($app['twig'])) {
            $app['twig']->addExtension(new MarkdownTwigExtension($app['markdown']));
        }
    }
}
