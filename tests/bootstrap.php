<?php

require __DIR__ . '/../bootstrap.php';

use Doctrine\ORM\EntityManager;

$dbParams = array(
    'driver' => 'pdo_sqlite',
    'dbname' => 'memory:dnp.db',
);

return $entityManager = EntityManager::create($dbParams, $config);