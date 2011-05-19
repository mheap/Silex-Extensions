GravatarExtension
=================

The *GravatarExtension* provides access to the Gravatar web service
through Sven Eisenschmidts's `Gravatar <https://github.com/fate/Gravatar-php>`_
library.

Parameters
----------

* **gravatar.cache_dir** (optional): A directory to cache the direct web service calls to gravatar.com

* **gravatar.cache_ttl** (optional): The time how long a cache entry will live, defaults to 360 seconds 

* **gravatar.options** (optional): An associative array of arguments for the Gravatar\\Service class

* **gravatar.class_path** (optional): Path to where the Gravatar library is located

Services
--------

* **gravatar**: Instance of Gravatar\\Service

* **gravatar.cache**: The cache layer if one is configured


Registering
-----------

Make sure you place a copy of *Gravatar* in the ``vendor/gravatar-php``
directory.

  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\GravatarExtension(), array(
        'gravatar.class_path' => __DIR__ . '/vendor/gravatar-php/src',
        'gravatar.cache_dir'  => sys_get_temp_dir() . '/gravatar',
        'gravatar.cache_ttl'  => 240, // 240 seconds
        'gravatar.options' => array(
            'size' => 100,
            'rating' => Gravatar\Service::RATING_G,
            'secure' => true,
            'default'   => Gravatar\Service::RATING_404,
            'force_default' => true
        )    
    ));
    
    $app->get('/', function() use($app) {
        return $app['gravatar']->get('sven.eisenschmidt@gmail.com');
    });
    
  In Twig templates you can do the following::

    {% if gravatar_exist('sven.eisenschmidt@gmail.com') %}
        Gravatar found
    {% endif %}

    <img src="{{ gravatar('sven.eisenschmidt@gmail.com', {'size': 50}) }}" />
