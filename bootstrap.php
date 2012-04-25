<?php

require_once __DIR__ . '/vendor/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespaces(array(
	'Phixtures' => __DIR__ . '/src',
	'Phixtures\\Test' => __DIR__ . '/test'
));
$loader->registerPrefix('Twig_', __DIR__ . '/vendor/Twig/lib');
