<?php

namespace DoctrineNaPratica\Model;

use DoctrineNaPratica\Test\TestCase;
use DoctrineNaPratica\Model\User;

/**
 * Class UserTest
 * @package DoctrineNaPratica\Model
 * @group Model
 */
class UserTest extends TestCase
{
    public function testUser()
    {
        $user = new User();
        $user->setName('Steve Jobs')
            ->setLogin('steve')
            ->setEmail('steve@apple.com')
            ->setAvatar('steve.png');

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());

        $saveduser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertInstanceOf(get_class($user), $saveduser);
        $this->assertEquals($user->getName(), $saveduser->getName());
    }
}