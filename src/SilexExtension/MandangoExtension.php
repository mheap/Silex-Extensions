<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Mandango\Mandango,
    Mandango\Connection,
    Mandango\Cache\FilesystemCache;

use Mandango\Mondator\Mondator;
class MandangoExtension implements ServiceProviderInterface
{
    public function register(Application $app)
    {  
                 
        $configuration = $app['mandango.configuration'];
        if(!isset($configuration['metadata_factory_class'])) {
            throw new \Exception("Missing param 'metadata_factory_class' in 'mandango.configuration'");
        }
        if(!isset($configuration['metadata_factory_output'])) {
            throw new \Exception("Missing param 'metadata_factory_output' in 'mandango.configuration'");
        }
        if(!isset($configuration['default_output'])) {
            throw new \Exception("Missing param 'default_output' in 'mandango.configuration'");
        }
        if(!isset($configuration['schema_file'])) {
            throw new \Exception("Missing param 'schema_file' in 'mandango.configuration'");
        }
            
        if(!isset($app['mandango.token'])) {
            throw new \Exception("Missing param 'mandango.token'");
        }
            
        $app['mandango'] = $app->share(function() use ($app) {
           
            $connections   = isset($app['mandango.connections']) ? $app['mandango.connections'] : array(
                'local' => array(
                    'host'     => 'mongodb://localhost:27017',
                    'database' => 'test'
                )
            );
            
            $default   = isset($app['mandango.default_connection']) ? $app['mandango.default_connection'] : 'local';
            $logging   = isset($app['mandango.logging']) ? (bool)$app['mandango.logging'] : false;
            $cache_dir = isset($app['mandango.cache_dir']) ? $app['mandango.cache_dir'] : sys_get_tempd_dir();
 
            $metadata = new $app['mandango.configuration']['metadata_factory_class'];
            $mandango = new Mandango($metadata, new FilesystemCache($cache_dir));
            
            foreach($connections as $name => $config) {
                $connections[$name] = new Connection($config['host'], $config['database']);
            }
            $mandango->setConnections($connections);
            $mandango->setDefaultConnectionName($default);
            
            return $mandango;
        });
        
        $app['mandango.mondator'] = $app->share(function() use ($app, $configuration) {
            
            $mondator = new Mondator();
            $mondator->setConfigClasses(require $configuration['schema_file']);
            $mondator->setExtensions(array(
                new \Mandango\Extension\Core(array(
                    'metadata_factory_class'    => $configuration['metadata_factory_class'],
                    'metadata_factory_output'   => $configuration['metadata_factory_output'],
                    'default_output'            => $configuration['default_output']
                )),
            ));
            
            return $mondator;
        });
        
        $app->get('/_mandango/generate/{token}', function($token) use ($app) {
            if($token !== $app['mandango.token']) {
                return 'Access Denied!';
            }        
            
            $app['mandango.mondator']->process();
            
            return 'Successfull generated models';
        });
        
        // autoloading Mandango library
        if (isset($app['mandango.class_path'])) {
            $app['autoloader']->registerNamespace('Mandango', 
                $app['mandango.class_path']);
            
            $app['autoloader']->registerNamespace('Mandango\Mondator', 
                $app['mandango.class_path'] . '/../vendor/mondator/src');
        }
        
        if(isset($configuration['default_output'])) {
            $outputDir = rtrim($configuration['default_output'], DIRECTORY_SEPARATOR);
            $outputDir = dirname($outputDir);
            
            $app['autoloader']->registerNamespace(
                isset($configuration['model_namespace']) ? 
                    $configuration['model_namespace'] : 'Model', $outputDir);
        } 
        
    }
    
    
}
