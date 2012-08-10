<?php

namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;

use SilexExtension\MarkdownExtension\MarkdownTwigExtension;

class MarkdownExtension implements ServiceProviderInterface
{
    public function boot(Application $app)
    {

    }

    public function register(Application $app)
    {
        $app['markdown'] = $app->share(function () use ($app) {
            $features = isset($app['markdown.features']) ? $app['markdown.features'] : array();
            return new MarkdownParser($features);
        });

        if (isset($app['twig'])) {
            $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
                $twig->addExtension(new MarkdownTwigExtension($app['markdown']));
                return $twig;
            }));
        }
    }
}
