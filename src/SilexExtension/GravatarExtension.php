<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use Gravatar\Service,
    Gravatar\Extension\Twig\GravatarExtension as TwigGravatarExtension;

class GravatarExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app['gravatar'] = $app->share(function () use ($app) {
            $options = isset($app['gravatar.options']) ? $app['gravatar.options'] : array();
            return new Service($options);
        });  
        
        // autoloading the predis library
        if (isset($app['gravatar.class_path'])) {
            $app['autoloader']->registerNamespace('Gravatar', $app['gravatar.class_path']);
        }  
        
        if (isset($app['twig'])) {
            $app['twig']->addExtension(new TwigGravatarExtension($app['gravatar']));
        }
    }
}
