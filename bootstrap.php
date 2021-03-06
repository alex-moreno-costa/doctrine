<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('DoctrineNaPratica', __DIR__ . '/src');

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$isDevMode = false;

$paths = array(__DIR__ . '/src/DoctrineNaPratica/Model');
$dbParams = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'Sigm@mmx4',
    'dbname' => 'dnp'
);

$config = Setup::createConfiguration($isDevMode);
$driver = new AnnotationDriver(new AnnotationReader(), $paths);
$config->setMetadataDriverImpl($driver);

AnnotationRegistry::registerFile(__DIR__ . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$entityManager = EntityManager::create($dbParams, $config);

$evm = $entityManager->getEventManager();
$entitySubscriber = new \DoctrineNaPratica\Model\Subscriber\EntitySubscriber();
$evm->addEventSubscriber($entitySubscriber);