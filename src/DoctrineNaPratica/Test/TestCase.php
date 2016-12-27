<?php

namespace DoctrineNaPratica\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class TestCase
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    protected $em = null;

    public function setUp()
    {
        $em = $this->getEntityManager();
        $tool = new SchemaTool($em);
        $classes = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($classes);
        parent::setUp();
    }

    public function tearDown()
    {
        $em = $this->getEntityManager();
        $tool = new SchemaTool($em);
        $classes = $em->getMetadataFactory()->getAllMetadata();
        $tool->dropSchema($classes);
        parent::tearDown();
    }

    protected function getEntityManager()
    {
        if (!$this->em) {
            $this->em = require __DIR__ . '/../../../tests/bootstrap.php';
        }

        return $this->em;
    }
}