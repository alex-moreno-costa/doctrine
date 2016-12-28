<?php

namespace DoctrineNaPratica\Model;

use Doctrine\DBAL\Connection;
use DoctrineNaPratica\Test\TestCase;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;

class DbalTest extends TestCase
{
    /**
     * @var Connection
     */
    private $conn;
    private $schema;

    public function setUp()
    {
        $connectionParams = array(
            'dbname' => 'sqlite:memory',
            'driver' => 'pdo_sqlite',
        );

        $this->conn = DriverManager::getConnection($connectionParams);

        $this->schema = new Schema();
        $usersTable = $this->schema->createTable("users");
        $usersTable->addColumn("id", "integer", array('autoincrement' => true));
        $usersTable->addColumn("name", "string", array("length" => 100));
        $usersTable->addColumn("login", "string", array("length" => 100));
        $usersTable->addColumn("email", "string", array("length" => 256));
        $usersTable->addColumn("avatar", "string", array("length" => 256));
        $usersTable->setPrimaryKey(array("id"));

        $plataform = $this->conn->getDatabasePlatform();
        $queries = $this->schema->toSql($plataform);

        foreach ($queries as $q) {
            $stmt = $this->conn->query($q);
        }
    }

    public function tearDown()
    {
        $this->schema->dropTable('users');
    }

    public function testUser()
    {
        $this->conn->beginTransaction();
        $this->conn->insert('users', array(
            'name' => 'Steve Jobs',
            'login' => 'steve',
            'email' => 'steve@apple.com',
            'avatar' => 'steve.png',
        ));

        $this->conn->insert('users', array(
            'name' => 'Bill Gates',
            'login' => 'bill',
            'email' => 'bill@microsoft.com',
            'avatar' => 'bill.png',
        ));

        $this->conn->commit();

        $sql = 'SELECT * FROM users';
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetchAll();

        $this->assertEquals(2, count($result));
        $this->assertEquals('steve', $result[0]['login']);
        $this->assertEquals('bill', $result[1]['login']);
    }

    public function testUserParameters()
    {
        //inicia uma transação
        $this->conn->beginTransaction();
        $this->conn->insert('users', array(
            'name' => 'Steve Jobs',
            'login' => 'steve',
            'email' => 'steve@apple.com',
            'avatar' => 'steve.png'
        ));

        $this->conn->insert('users', array(
            'name' => 'Bill Gates',
            'login' => 'bill',
            'email' => 'bill@microsoft.com',
            'avatar' => 'bill.png'
        ));

        //commit da transação
        $this->conn->commit();

        //usando placeholders
        $sql = 'SELECT * FROM users u WHERE u.login = ? or u.email = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, 'steve');
        $stmt->bindValue(2, 'steve@apple.com');
        $stmt->execute();
        $result = $stmt->fetchAll();

        $this->assertEquals(1, count($result));
        $this->assertEquals('steve', $result[0]['login']);

        //usando Named parameters
        $sql = 'SELECT * FROM users u WHERE u.login = :login or u.email = :email';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('login', 'steve');
        $stmt->bindValue('email', 'steve@apple.com');
        $stmt->execute();
        $result = $stmt->fetchAll();

        $this->assertEquals(1, count($result));
        $this->assertEquals('steve', $result[0]['login']);
    }
}