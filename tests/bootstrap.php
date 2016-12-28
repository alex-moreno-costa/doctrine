<?php

require __DIR__ . '/../bootstrap.php';

use Doctrine\ORM\EntityManager;

$dbParams = array(
    'driver' => 'pdo_sqlite',
    'dbname' => 'memory:dnp.db',
);

$entityManager = EntityManager::create($dbParams, $config);

$evm = $entityManager->getEventManager();
$entitySubscriber = new \DoctrineNaPratica\Model\Subscriber\EntitySubscriber();
$evm->addEventSubscriber($entitySubscriber);

return $entityManager;