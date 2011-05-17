<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;

use Mandango\Mandango,
    Mandango\Connection,
    Mandango\Cache\FilesystemCache;

use Mandango\Mondator\Mondator;
class MandangoExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $defConnOpts = array(
            'host'     => 'mongodb://localhost:27017',
            'database' => 'test'
        );
        
        
        $app['mandango'] = $app->share(function() use ($app, $defConnOpts) {
            
            $connOpts = isset($app['mandango.connection']) ?
                array_merge($defConnOpts, $app['mandango.connection']) : $defConnOpts;
            
            if(!isset($app['mandango.configuration'])) {
                throw new \Exeption("Missing 'mandango.configuration'");
            }
            
            $config = $app['mandango.configuration'];
            if(!isset($config['metadata_factory_class'])) {
                throw new \Exeption("Missing param metadata_factory_class in 'mandango.configuration'");
            }
            if(!isset($config['cache_dir'])) {
                throw new \Exeption("Missing param cache_dir in 'mandango.configuration'");
            }
            
            $connection = new Connection($connOpts['host'], $connOpts['database']);
            $metadata   = new $config['metadata_factory_class'];
            $querycache = new FilesystemCache($config['cache_dir']);
            
            $mandango = new Mandango($metadata, $querycache);
            $mandango->setConnection('default', $connection);
            $mandango->setDefaultConnectionName('default');
        
            return $mandango;
        });
        
        $app['mandango.mondator'] = $app->share(function() use ($app, $defConnOpts) {
            
            if(!isset($app['mandango.configuration'])) {
                throw new \Exeption("Missing 'mandango.configuration'");
            }
            
            $config = $app['mandango.configuration'];
            if(!isset($config['metadata_factory_class'])) {
                throw new \Exeption("Missing param metadata_factory_class in 'mandango.configuration'");
            }
            if(!isset($config['metadata_factory_output'])) {
                throw new \Exeption("Missing param metadata_factory_output in 'mandango.configuration'");
            }
            if(!isset($config['model_file'])) {
                throw new \Exeption("Missing param model_file in 'mandango.configuration'");
            }
            if(!isset($config['default_output'])) {
                throw new \Exeption("Missing param default_output in 'mandango.configuration'");
            }
            
            $modelConfig = require $config['model_file'];
    
            $mondator = new Mondator();
            $mondator->setConfigClasses($modelConfig);
            $mondator->setExtensions(array(
                new \Mandango\Extension\Core(array(
                    'metadata_factory_class'  => $config['metadata_factory_class'],
                    'metadata_factory_output' => $config['metadata_factory_output'],
                    'default_output'  => $config['default_output']
                )),
            ));
            
            return $mondator;
        });
        
        $app->get('/_mandango/{token}', function($token) use ($app) {
            if($token !== $app['mandango.token']) {
                return 'Access Denied!';
            }        
                
            $app['mandango.mondator']->process();   
            return 'Success: Generated classes';
        });
        
        
        // autoloading Mandango library
        if (isset($app['mandango.class_path'])) {
            $app['autoloader']->registerNamespace('Mandango', 
                $app['mandango.class_path']);
            
            $app['autoloader']->registerNamespace('Mandango\Mondator', 
                $app['mandango.class_path'] . '/../vendor/mondator/src');
        }
        
        // autoloading models
        if (isset($app['mandango.configuration'])) {
            if(isset($app['mandango.configuration']['default_output'])) {
                $outputDir = rtrim($app['mandango.configuration']['default_output'], DIRECTORY_SEPARATOR);
                $outputDir = dirname($outputDir);
            
                $app['autoloader']->registerNamespace(
                    isset($app['mandango.configuration']['model_namespace']) ? 
                        $app['mandango.configuration']['model_namespace'] : 'Model', $outputDir);
            }      
        }
    }
    
    
}
