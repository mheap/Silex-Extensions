<?php

namespace SilexExtension\Tests\Extension;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;

use SilexExtension\PredisExtension;

class PredisExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!is_dir(__DIR__ . '/../../../vendor/predis/lib')) {
            $this->markTestSkipped('Predis was not installed.');
        }
    }
    
    public function testRegister()
    {
        $app = new Application();
        $app->register(new PredisExtension(), array(
            'predis.class_path' => __DIR__ . '/../../../vendor/predis/lib',
            'predis.config'  => array(
                'prefix' => 'predis__'
            )
        ));
            
        $app->get('/', function() use($app) {
            $app['predis'];    
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $this->assertInstanceOf('Predis\Client', $app['predis']);
        $this->assertSame('predis__', $app['predis']->getOptions()->prefix->getPrefix());
    }
    
    /**
     *
     * @expectedException Predis\ConnectionException
     */
    public function testFailedConnection()
    {
        $app = new Application();
        $app->register(new PredisExtension(), array(
            'predis.class_path' => __DIR__ . '/../../../vendor/predis/lib',
            'predis.server'  => array(
                'port' => 0,
                'host' => '0.0.0.0'
            )
        ));
            
        $app->get('/', function() use($app) {
            $app['predis'];    
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $app['predis']->connect();
    }
    
    public function testSetAndGet()
    {
        $app = new Application();
        $app->register(new PredisExtension(), array(
            'predis.class_path' => __DIR__ . '/../../../vendor/predis/lib',
            'predis.config'  => array(
                'prefix' => 'predis__'
            )
        ));
            
        $app->get('/', function() use($app) {
            $app['predis'];    
        });
        $request = Request::create('/');
        $app->handle($request);
            
        $testvalue = 'my_test_value'; 
        $app['predis']->set('my_test_key', $testvalue);
        
        $this->assertSame($testvalue, $app['predis']->get('my_test_key'));
    }
    
    
    
}