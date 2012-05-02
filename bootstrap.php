<?php

require_once __DIR__ . '/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespaces(array(
	'Phixtures' => __DIR__ . '/src',
	'Phixtures\\Test' => __DIR__ . '/test',
	'Symfony' => __DIR__ . '/vendor',
));
$loader->registerPrefixes(array(
	'Twig_' => __DIR__ . '/vendor/Twig/lib',
	'Pimple' => __DIR__ . '/vendor/Pimple/lib',
));
