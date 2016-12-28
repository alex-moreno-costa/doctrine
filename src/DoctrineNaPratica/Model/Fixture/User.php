<?php

namespace DoctrineNaPratica\Model\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use DoctrineNaPratica\Model\User as ModelUser;
use Doctrine\Common\Persistence\ObjectManager;

class User implements FixtureInterface
{
    private $data = array(
        array(
            'name' => 'Elton Minetto',
            'login' => 'eminetto',
            'email' => 'eminetto@coderockr.com',
        ),
        array(
            'name' => 'Steve Jobs',
            'login' => 'steve',
            'email' => 'steve@apple.com',
        ),
        array(
            'name' => 'Bill Gates',
            'login' => 'bill',
            'email' => 'bill@microsoft.com',
        )
    );

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $d) {
            $user = new ModelUser();
            $user->setName($d['name'])
                ->setEmail($d['email'])
                ->setLogin($d['login']);

            $manager->persist($user);
        }

        $manager->flush();
    }

}