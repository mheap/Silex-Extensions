Silex Extensions
================

Fully tested collection of extensions for `Silex <https://github.com/fabot/silex>`_.


Available Extensions
--------------------

* **EmbedlyExtension** (embed.ly web service, uses embedly-php fork)
* **GravatarExtension** (gravatar.com web service, uses grvatar-php library)
* **MandangoExtension** (Mandango ODM for MongoDB NoSQL database)
* **MarkdownExtension** (*not yet released*)
* **MemcacheExtension** (Memcache caching layer, non-permanent key/value store, uses pecl-memcache or pecl-memcached)
* **MongoDbExtension** (MongoDB NoSQL database, uses Doctrine\\MongoDB library)
* **PredisExtension** (Redis key/value store, uses Predis php library)

Installation
------------

Run the following commands inside your Silex directory:

    git clone git@github.com:fate/Silex-Extensions.git vendor/silex-extension
    
To install vendor depedencies run 

    sh ./vendors.sh
    
And install all submodules in vendor/mongodb and vendor/mandango

    git submodule update --init
 
Add the library to the Silex autoloader

    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/vendor/silex-extension/src');

More Information
----------------

Read the documentation files under */doc*.

License
-------

'Silex Extensions' are licensed under the MIT license.
