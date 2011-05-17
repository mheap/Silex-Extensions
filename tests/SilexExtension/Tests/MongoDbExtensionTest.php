<?php

namespace SilexExtension\Tests\Extension;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;

use SilexExtension\MongoDbExtension;

class MongoDbExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!is_dir(__DIR__ . '/../../../vendor/mongodb/lib')) {
            $this->markTestSkipped('Doctrine\MongoDB was not installed.');
        }
    }
    
    public function testRegister()
    {
        $app = new Application();
        $app->register(new MongoDbExtension(), array(
            'mongodb.class_path' => __DIR__ . '/../../../vendor/mongodb/lib'
        ));
        
        $app->get('/', function() use($app) {
            $app['mongodb'];    
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $this->assertInstanceOf('Doctrine\MongoDB\Connection', $app['mongodb']);
        $this->assertInstanceOf('Doctrine\MongoDB\Configuration', $app['mongodb.configuration']);
        $this->assertInstanceOf('Doctrine\Common\EventManager', $app['mongodb.eventmanager']);
    }
    
    public function testConfigurationAndEventManager()
    {
        $test = $this;
        
        $app = new Application();
        $app->register(new MongoDbExtension(), array(
            'mongodb.class_path' => __DIR__ . '/../../../vendor/mongodb/lib',
            'mongodb.connection' => array(
                'configuration' => function($configuration) use($test) {
                    $test->assertInstanceOf('Doctrine\MongoDB\Configuration', $configuration);     
                },  
                'eventmanager' => function($eventmanager) use($test) {
                    $test->assertInstanceOf('Doctrine\Common\EventManager', $eventmanager);     
                }   
            )
        ));
        
        $app->get('/', function() use($app) {
            $app['mongodb'];    
        });
        $request = Request::create('/');
        $app->handle($request);
    }
    
    public function testOptions()
    {
        $test = $this;
        
        $app = new Application();
        $app->register(new MongoDbExtension(), array(
            'mongodb.class_path' => __DIR__ . '/../../../vendor/mongodb/lib',
            'mongodb.connection' => array(
                'server'    => '127.0.0.1:9999',
                'options'   => array(
                    'connect' => false,
                    'persistent' => 'c83d9d59bf24ae3a6dc5a30cb47ebbba'
                )
            )
        ));
        
        $app->get('/', function() use($app) {
            $app['mongodb'];    
        });
        $request = Request::create('/');
        $app->handle($request);
    
        $app['mongodb']->initialize();
        $this->assertSame('127.0.0.1:9999', $app['mongodb']->getServer());
        
        $mongo = $app['mongodb']->getMongo();
        $reflect  = new \ReflectionClass($mongo);
        $property = $reflect->getProperty('persistent');
        $property->setAccessible(true);
        $this->assertSame('c83d9d59bf24ae3a6dc5a30cb47ebbba', $property->getValue($mongo));
    }
    
    
    
}