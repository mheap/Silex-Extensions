PredisExtension
================

The *PredisExtension* provides access to a Redis instances
through Daniele Alessandri's `Predis <https://github.com/doctrine/mongodb>`_
library.

Parameters
----------

* **predis.class_path** (optional): Path to where the Predis library is located.

* **predis.server** (optional): An associative array of arguments to configure the
  redis client instance, takes the same structure of options as the first argument of the
  Predis\\Client Constructor.

* **predis.config** (optional): An associative array of arguments to configure the
  redis client instance, takes the same structure of options as the second argument of the
  Predis\\Client Constructor.

Services
--------

* **predis**: Instance of Predis\\Client

Registering
-----------

Make sure you place a copy of *Predis* in the ``vendor/predis``
directory.

  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\PredisExtension(), array(
        'predis.class_path'    => __DIR__ . '/vendor/predis/lib',
        'predis.server'  => array(
            'host' => '127.0.0.1',
            'port' => 6379
        ),
        'predis.config'  => array(
            'prefix' => 'predis__'
        )
    ));
    
Usage
-----

    $app->get('/', function() use($app) {
        $app['predis']->set('my_value', 'somevalue');
        $value = $app['predis']->get('my_value');
    });