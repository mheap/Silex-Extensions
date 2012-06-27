<?php

namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Assetic\AssetManager,
    Assetic\FilterManager,
    Assetic\AssetWriter,
    Assetic\Asset\AssetCache,
    Assetic\Factory\AssetFactory,
    Assetic\Factory\LazyAssetManager,
    Assetic\Cache\FilesystemCache,
    Assetic\Extension\Twig\AsseticExtension as TwigAsseticExtension;

class AsseticExtension implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['assetic.options'] = array_replace(array(
            'debug' => false,
            'formulae_cache_dir' => null,
        ), isset($app['assetic.options']) ? $app['assetic.options'] : array());

        /**
         * Asset Factory conifguration happens here
         */
        $app['assetic'] = $app->share(function () use ($app) {
            // initializing lazy asset manager
            if (isset($app['assetic.formulae']) &&
               !is_array($app['assetic.formulae']) &&
               !empty($app['assetic.formulae'])
            ) {
                $app['assetic.lazy_asset_manager'];
            }

            return $app['assetic.factory'];
        });

        /**
         * Factory
         * @return Assetic\Factory\AssetFactory
         */
        $app['assetic.factory'] = $app->share(function() use ($app) {
            $options = $app['assetic.options'];
            $factory = new AssetFactory($app['assetic.path_to_web'], $options['debug']);
            $factory->setAssetManager($app['assetic.asset_manager']);
            $factory->setFilterManager($app['assetic.filter_manager']);
            return $factory;
        });

        /**
         * Writes down all lazy asset manager and asset managers assets
         */
        $app->after(function() use ($app) {
            $app['assetic.asset_writer']->writeManagerAssets(
                $app['assetic.lazy_asset_manager']);
            $app['assetic.asset_writer']->writeManagerAssets(
                $app['assetic.asset_manager']);
        });

        /**
         * Asset writer, writes to the 'assetic.path_to_web' folder
         */
        $app['assetic.asset_writer'] = $app->share(function () use ($app) {
            return new AssetWriter($app['assetic.path_to_web']);
        });

        /**
         * Asset manager, can be accessed via $app['assetic.asset_manager']
         * and can be configured via $app['assetic.assets'], just provide a
         * protected callback $app->protect(function($am) { }) and add
         * your assets inside the function to asset manager ($am->set())
         */
        $app['assetic.asset_manager'] = $app->share(function () use ($app) {
            $assets = isset($app['assetic.assets']) ? $app['assetic.assets'] : function() {};
            $manager = new AssetManager();

            call_user_func_array($assets, array($manager, $app['assetic.filter_manager']));
            return $manager;
        });

        /**
         * Filter manager, can be accessed via $app['assetic.filter_manager']
         * and can be configured via $app['assetic.filters'], just provide a
         * protected callback $app->protect(function($fm) { }) and add
         * your filters inside the function to filter manager ($fm->set())
         */
        $app['assetic.filter_manager'] = $app->share(function () use ($app) {
            $filters = isset($app['assetic.filters']) ? $app['assetic.filters'] : function() {};
            $manager = new FilterManager();

            call_user_func_array($filters, array($manager));
            return $manager;
        });

        /**
         * Lazy asset manager for loading assets from $app['assetic.formulae']
         * (will be later maybe removed)
         */
        $app['assetic.lazy_asset_manager'] = $app->share(function () use ($app) {
            $formulae = isset($app['assetic.formulae']) ? $app['assetic.formulae'] : array();
            $options  = $app['assetic.options'];
            $lazy     = new LazyAssetmanager($app['assetic.factory']);

            if (empty($formulae)) {
                return $lazy;
            }

            foreach ($formulae as $name => $formula) {
                $lazy->setFormula($name, $formula);
            }

            if ($options['formulae_cache_dir'] !== null && $options['debug'] !== true) {
                foreach ($lazy->getNames() as $name) {
                    $lazy->set($name, new AssetCache(
                        $lazy->get($name),
                        new FilesystemCache($options['formulae_cache_dir'])
                    ));
                }
            }
            return $lazy;
        });

        if(isset($app['twig'])) {
            $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
                $twig->addExtension(new TwigAsseticExtension($app['assetic.factory']));
                return $twig;
            }));
        }
    }

    /**
     * Bootstraps the application.
     *
     * @param \Silex\Application $app The application
     */
    function boot(Application $app)
    {
    }
}
