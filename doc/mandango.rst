MandangoExtension
================

The *MandangoExtension* provides access to Mandango instances
through Pablo Díez's `Mandango <https://github.com/mandango/mandango>`_
library.

Parameters
----------

* **mandango.token**: A security token, which acts as a parameter to the 
  _mandango/generate route to generate the model

* **mandango.cache_dir**: The directory for cahcing queries.

* **mandango.default_connection** (optional): The name of the default connection from 
  mandango.connections, defaults to local

* **mandango.connections** (optional): An associative array of connections, 

* **mandango.class_path** (optional): Path to where the Mandango library is located.

Routes
------

* **/_mandango/generate/{$token}**: A route to generate the model and mapping files.
  **Will be soon deprecated.**

Services
--------

* **mandango**: The Mandango instance.

* **mandango.mondator**: A configured mondator instance.

Registering
-----------

Make sure you place a copy of *Mandango* in the ``vendor/mandango``
directory.

  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\MandangoExtension(), array(
        'mandango.class_path'           => __DIR__ . '/vendor/mandango/src',
        'mandango.token'                => '4dd399ea814c',
        'mandango.cache_dir'            => __DIR__ . '/odm/cache',
        'mandango.default_connection'   => 'local',
    
        'mandango.connections' => array(
            'local' => array(
                'host'     => 'mongodb://localhost:27017',
                'database' => 'mandango'
            )
        ),
    
        'mandango.configuration' => array(
            'metadata_factory_class'    => 'Model\Mapping\Metadata',
            'metadata_factory_output'   => __DIR__ . '/odm/Model/Mapping',
            'default_output'            => __DIR__ . '/odm/Model',
            'schema_file'               => __DIR__ . '/odm/schema.php'
        )
    ));
    
Usage
-----

    $app->get('/blog', function() use($app) {
    
        $articles = $app['mandango']
            ->getRepository('Model\Article')
            ->count();
    
        return "Found {$articles}";
    });
    
    
    $app->get('/blog/new', function() use($app) {
    
        $article = $app['mandango']->create('Model\Article');
        $article->setTitle('MY Article');
        $article->setContent('Lorem ipsum ...');
        $article->save();
        
        // or
        
        $article = new \Model\Article;
        $article->setTitle('My Article');
        $article->setContent('Lorem ipsum ...');
        
        $app['mandango']->persist($article);
        $app['mandango']->flush();
    });