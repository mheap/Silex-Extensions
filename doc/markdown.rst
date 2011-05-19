MarkdownExtension
=================

The *MarkdownExtension* provides the ability to parse markdown through 
KpnLab's `MarkdownBundle <https://github.com/knplabs/MarkdownBundle>`_ .

Parameters
----------

* **markdown.features** (optional): An associative of features with boolean values for configuring the KnpLab's Markdown parser

* **markdown.class_path** (optional): Path to where the MarkdownBundle library is located.

Services
--------

* **markdown**: The markdown parser instance

Registering
-----------

Make sure you place a copy of *MarkdownBundle* in the ``vendor/kpnlabs-markdown``
directory.

  Example registration and configuration::

    // add SilexExtension library to the autoloader 
    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/path/to/silex-extensions');
    $app->register(new SilexExtension\MarkdownExtension(), array(
        'markdown.class_path' => __DIR__ . '/vendor/knplabs-markdown',
        'markdown.features'   => array(
            'header' => false, // don't render first level headlines
        ),
    ));

    $app->get('/', function() use($app) {    
        
        return $app['markdown']->transform("My small Headline \n ----");
        
        // or with twig
        
        return $app['twig']->render('my-markdown.twig');
    });

  In Twig templates you can do the following::

    {{ '
 
    Hey
    ===

    *Markdown support is working*

    ' | markdown }}
    