<?php

namespace DoctrineNaPratica\Model;

use DoctrineNaPratica\Test\TestCase;
use DoctrineNaPratica\Model\User;
use DoctrineNaPratica\Model\Course;
use DoctrineNaPratica\Model\Lesson;
use DoctrineNaPratica\Model\Progress;
use DoctrineNaPratica\Model\Enrollment;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Model
 */
class DqlTest extends TestCase
{
    //testa a dql dos Users
    public function testUser()
    {
        $userA = $this->buildUser('userA');
        $userB = $this->buildUser('userB');

        $this->getEntityManager()->persist($userA);
        $this->getEntityManager()->persist($userB);
        $this->getEntityManager()->flush();

        $query = $this->em->createQuery('SELECT u FROM DoctrineNapratica\Model\User u');
        $users = $query->getResult();

        $this->assertEquals(2, count($users));
        $this->assertEquals($userA->getId(), $users[0]->getId());
        $this->assertEquals($userB->getId(), $users[1]->getId());

        $this->assertInstanceOf(get_class($userA), $users[0]);
    }

    //buscar lições que tenham 100 de progresso
    public function testUserLesson()
    {
        $userA = $this->buildUser('UserA');

        $course = new Course;
        $course->setName('PHP');
        $course->setDescription('Curso de PHP');
        $course->setValue(100);
        $course->setTeacher($userA);

        $lessonA = new Lesson;
        $lessonA->setName('Arrays');
        $lessonA->setDescription('Aula sobre Arrays');
        $lessonA->setCourse($course);

        $lessonB = new Lesson;
        $lessonB->setName('Datas');
        $lessonB->setDescription('Aula sobre Datas');
        $lessonB->setCourse($course);

        $lessonCollection = new ArrayCollection;
        $lessonCollection->add($lessonA);
        $lessonCollection->add($lessonB);

        $userA->setLessonCollection($lessonCollection);

        $enrollment = new Enrollment;
        $enrollment->setUser($userA);
        $enrollment->setCourse($course);

        $progressA = new Progress;
        $progressA->setPercent(100);
        $progressA->setUser($userA);
        $progressA->setLesson($lessonA);
        $progressA->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

        $progressB = new Progress;
        $progressB->setPercent(90);
        $progressB->setUser($userA);
        $progressB->setLesson($lessonB);
        $progressB->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

        $this->getEntityManager()->persist($userA);
        $this->getEntityManager()->persist($enrollment);
        $this->getEntityManager()->persist($progressA);
        $this->getEntityManager()->persist($progressB);
        $this->getEntityManager()->flush();

        $query = $this->em->createQuery(
            'SELECT p FROM DoctrineNapratica\Model\Progress p JOIN p.lesson l JOIN l.course c WHERE p.percent = 100'
        );
        $result = $query->getResult();

        $this->assertEquals(1, count($result));
        $this->assertEquals($userA->getId(), $result[0]->getUser()->getId());
        $this->assertEquals($lessonA->getId(), $result[0]->getLesson()->getId());
    }

    //testa a dql dos Users com parametros
    public function testUserParameters()
    {
        $userA = $this->buildUser('userA');
        $userB = $this->buildUser('userB');

        $this->getEntityManager()->persist($userA);
        $this->getEntityManager()->persist($userB);
        $this->getEntityManager()->flush();

        $query = $this->em->createQuery(
            'SELECT u FROM DoctrineNapratica\Model\User u WHERE u.login = :login or u.email = :email'
        );
        $query->setParameters(
            array('login' => 'userA', 'email' => 'userA@domain.com')
        );

        $users = $query->getResult();
        $this->assertEquals(1, count($users));
        $this->assertEquals($userA->getId(), $users[0]->getId());

        $this->assertInstanceOf(get_class($userA), $users[0]);
    }

    //cria um User
    private function buildUser($login)
    {
        $user = new User;
        $user->setName($login . ' name');
        $user->setLogin($login);
        $user->setEmail($login . '@domain.com');
        $user->setAvatar($login . '.png');
        return $user;
    }

    //cria um Course
    private function buildCourse($courseName)
    {
        $teacher = $this->buildUser();
        //login é unique
        $teacher->setLogin('jobs'.$courseName);

        $course = new Course;
        $course->setName($courseName);
        $course->setDescription('Curso de PHP');
        $course->setValue(100);
        $course->setTeacher($teacher);

        return $course;
    }

    public function testHydrators()
    {
        $userA = $this->buildUser('userA');

        $this->getEntityManager()->persist($userA);
        $this->getEntityManager()->flush();

        $query = $this->em->createQuery('SELECT u FROM DoctrineNaPratica\Model\User u');
        $users = $query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
        $this->assertInstanceOf(get_class($userA), $users[0]);
        $this->assertEquals($userA->getId(), $users[0]->getId());

        $query = $this->em->createQuery('SELECT u FROM DoctrineNaPratica\Model\User u');
        $users = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals($userA->getId(), $users[0]['id']);

        $query = $this->em->createQuery('SELECT u FROM DoctrineNaPratica\Model\User u');
        $users = $query->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals($userA->getId(), $users[0]['u_id']);

        $query = $this->em->createQuery('SELECT u.login FROM DoctrineNaPratica\Model\User u');
        $login = $query->getResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals($userA->getLogin(), $login);
    }
}