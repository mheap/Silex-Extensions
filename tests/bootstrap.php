<?php

require_once __DIR__ . '/../silex.phar';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespace('SilexExtension', __DIR__ . '/../src');
$loader->register();