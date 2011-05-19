<?php

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.class_path' => __DIR__ . '/../vendor/twig/lib',
    'twig.path'       => __DIR__ . '/twig'
));
        
$app['autoloader']->registerNamespace('SilexExtension', __DIR__ . '/../src');
$app->register(new SilexExtension\MarkdownExtension(), array(
    'markdown.class_path' => __DIR__ . '/../vendor/knplabs-markdown',
    'markdown.features'   => array(
        'header' => true,
        'list' => true,
        'horizontal_rule' => true,
        'table' => true,
        'foot_note' => true,
        'fenced_code_block' => true,
        'abbreviation' => true,
        'definition_list' => true,
        'inline_link' => true,
        'reference_link' => true,
        'shortcut_link' => true,
        'block_quote' => true,
        'code_block' => true,
        'html_block' => true,
        'auto_link' => true,
        'auto_mailto' => true,
        'entities' => false
    ),
));

$app->get('/', function() use($app) {
    return $app['twig']->render('markdown.twig');
});

$app->run();   