<?php

include __DIR__ . '/bootstrap.php';
include __DIR__ . '/cli-config.php';

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use DoctrineNaPratica\Model\Fixture\Course as FixtureCourse;
use DoctrineNaPratica\Model\Fixture\User as FixtureUser;

$loader = new Loader();
$loader->addFixture(new FixtureUser());
$loader->addFixture(new FixtureCourse());

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures(), false);