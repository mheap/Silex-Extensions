MemcacheExtension
================

The *MemcacheExtension* provides access to Memcache instances
either through pecl-memcache or pecl-memcached 

Parameters
----------

* **memcache.library** (optional): library to use, memcache or memcached,
  by default *memcached*

* **memcache.server** (optional): An array servers which are added via addSever, please be aware
  that memcache and memcached* have different method signatures after the second argument for
  addServer. See `Memcache <http://de2.php.net/manual/en/memcache.addserver.php>` and
  `Memcached <http://de2.php.net/manual/en/memcache.addserver.php>`

Services
--------

* **memcache**: The memcache instance

Registering
-----------
  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\MemcacheExtension(), array(
        'memcache.library'    => 'memcached',
        'memcache.server' => array(
            array('127.0.0.1', 11211)    
        )
    ));
    
Usage
-----

    $app->get('/', function() use($app) {
        $app['memcache']->set('my_value', 'somevalue');
        $value = $app['memcache']->get('my_value');
    });
    
    
    
    
    
    
    
    
    
    