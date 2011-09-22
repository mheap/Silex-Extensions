<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;


class MemcacheExtension implements ServiceProviderInterface
{
    public function register(Application $app)
    {  
        $app['memcache'] = $app->share(function () use ($app) {
            
            $library = isset($app['memcache.library']) ? strtolower($app['memcache.library']) : 'memcached';
            $servers = isset($app['memcache.server']) ? $app['memcache.server'] : array(
                array('127.0.0.1', 11211)     
            );
            
            if($library == 'memcache') {
                $memcache = new \Memcache();
            } else
            
            if($library == 'memcached') {
                $memcache = new \Memcached();
            } else {
                throw new \Exception("Unsupported library '{$library}, choose between 'Memcache' or 'Memcached'");
            }

            foreach($servers as $config) {
                call_user_func_array(array($memcache, 'addServer'), array_values($config));
            }
                
            return $memcache;
        });
    }
}
