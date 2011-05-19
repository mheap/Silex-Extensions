EmbedlyExtension
================

The *EmbedlyExtension* provides access to the Embed.ly web service
through Sven Eisenschmidts's fork of `Embedly-php <https://github.com/fate/embedly-php>`_
library.

Parameters
----------

* **embedly.options** (optional): An associative array of arguments for the Embedly\\Embedly class

* **embedly.class_path** (optional): Path to where the Embedly library is located.

Services
--------

* **embedly**: Instance of Embedly\\Embedly

Registering
-----------

Make sure you place a copy of *Embedly-php* in the ``vendor/embedly-php``
directory.

  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\EmbedlyExtension(), array(
        'embedly.class_path' => __DIR__ . '/vendor/embedly-php/src',
        'embedly.options' => array(
            'user_agent' => 'My custom user agent',
            'key' => null // your api key
        )    
    ));
    
    $app->get('/', function() use($app) {
        
        // avaiable Embedly methods
        // - oembed
        // - preview
        // - objectify
        
        // for a single embed
        $embed = $app['embedly']->oembed('http://www.youtube.com/watch?v=c9BA5e2Of_U');
        return $embed->html;
        
        // or for multiple embeds
        $embeds = $app['embedly']->oembed(array(
            'urls' => array(
                'http://www.youtube.com/watch?v=c9BA5e2Of_U',
                'http://www.youtube.com/watch?v=asdasdasd_C',
                'http://www.youtube.com/watch?v=xyz12312312'
            )    
        ));
        
    });
    
  In Twig templates you can do the following

    {{ embedly_oembed('http://www.youtube.com/watch?v=c9BA5e2Of_U').html|raw }}
