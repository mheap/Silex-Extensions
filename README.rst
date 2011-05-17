Silex Extensions
================

Available Extensions?
--------------

* MongoDbExtension (Uses Doctrine\\MongoDB)

Installation
------------

Run the following commands inside your Silex directory:

    git clone git@github.com:fate/Silex-Extensions.git vendor/silex-extension
 
Add the library to the Silex autoloader

    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/vendor/silex-extension/src');

More Information
----------------

Read the documentation files under */doc*.

License
-------

'Silex Extensions' are licensed under the MIT license.