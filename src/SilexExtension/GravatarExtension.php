<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use SilexExtension\GravatarExtension\Service as GravatarService,
    SilexExtension\GravatarExtension\Twig\GravatarExtension as TwigGravatarExtension;

class GravatarExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app['gravatar'] = $app->share(function () use ($app) {
            $options = isset($app['gravatar.options']) ? $app['gravatar.options'] : array();
            
            print_p($options);
            return new GravatarService($options);
        });        
    }
}
