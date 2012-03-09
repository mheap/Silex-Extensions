Silex Extensions
================

Collection of extensions for `Silex <https://github.com/fabot/silex>`_.

Available Extensions
--------------------

* **AsseticExtension** (Assetic asset management library)
* **EmbedlyExtension** (embed.ly web service, uses embedly-php fork)
* **GravatarExtension** (gravatar.com web service, uses grvatar-php library)
* **MandangoExtension** (Mandango ODM for MongoDB NoSQL database)
* **MarkdownExtension** (Markdown support, uses KnpLabs\\MarkdownBundle)
* **MemcacheExtension** (Memcache caching layer, non-permanent key/value store, uses pecl-memcache or pecl-memcached)
* **MongoDbExtension** (MongoDB NoSQL database, uses Doctrine\\MongoDB library)
* **PredisExtension** (Redis key/value store, uses Predis php library)

Extensions with Twig Support
----------------------------

* **AsseticExtension**
* **EmbedlyExtension**
* **GravatarExtension**
* **MarkdownExtension**

Installation
------------

Create a composer.json in your projects root-directory

    {
        "require": {
            "fate/Silex-Extensions": "*"
        }
    }

and run

    curl -s http://getcomposer.org/installer | php
    php composer.phar install


Add the library to the Silex autoloader

    $app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/vendor/silex-extension/src');

More Information
----------------

Read the documentation files under */doc*.

License
-------

'Silex Extensions' are licensed under the MIT license.
