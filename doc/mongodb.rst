MongoDbExtension
================

The *MongoDbExtension* provides asset to MongoDB instances
through Doctrine's `MongoDB <https://github.com/doctrine/mongodb>`_
library.

Parameters
----------

* **assetic.connection**: An associative array of arguments for the MongoDB Connection class

* **assetic.connection => server** (optional): The server to connect to. 

* **assetic.connection => options** (optional): An associative array of options,
  passed in as the second argument to the Constructor, see Doctrine MongoDB documentation
  for all available options.

* **assetic.connection => configuration** (optional): A callback function to configure 
  (add logger, ...) the Configuration instance before passing it to the Constructor of the 
  Connection class.

* **assetic.connection => eventmanager** (optional): A callback function to configure 
  (add listener, ...) the EventManager instance before passing it to the Constructor of the 
  Connection class.

* **assetic.class_path** (optional): Path to where the Doctrine MongoDB
  library is located.

Services
--------

* **mongodb**: Instance of Doctrine MongoDB Connection

* **mongodb.configuration**: Instance of Doctrine MongoDB Configuration

* **mongodb.event_manager**: Instance of Doctrine Common EventManager


Registering
-----------

Make sure you place a copy of *Doctrine\MongoDB* in the ``vendor/mongodb``
directory.

  Example registration and configuration::

    // add your 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\MongoDbExtension(), array(
        'mongodb.class_path'    => __DIR__ . '/vendor/mongodb/lib',
        'mongodb.connection'    => array(
            'configuration' => function($configuration) {
                $configuration->setLoggerCallable(function($logs) {
                    print_r($logs);
                });    
            },
            'eventmanager' => function($eventmanager) {
                $eventmanager->addEventListener('postConnect', new MyListener());
            }
        )
    ));

