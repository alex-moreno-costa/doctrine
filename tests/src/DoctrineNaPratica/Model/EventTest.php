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
class EventTest extends TestCase
{
    //testa a execução do evento
    public function testUserCertificate()
    {
        $userA = $this->buildUser('UserA');
        $teacher = $this->buildUser('Teacher');

        $course = new Course;
        $course->setName('PHP');
        $course->setDescription('Curso de PHP');
        $course->setValue(100);
        $course->setTeacher($teacher);

        $lessonA = new Lesson;
        $lessonA->setName('Arrays');
        $lessonA->setDescription('Aula sobre Arrays');
        $lessonA->setCourse($course);

        $lessonB = new Lesson;
        $lessonB->setName('Datas');
        $lessonB->setDescription('Aula sobre Datas');
        $lessonB->setCourse($course);

        $enrollment = new Enrollment;
        $enrollment->setUser($userA);
        $enrollment->setCourse($course);

        $lessonCollection = new ArrayCollection;
        $lessonCollection->add($lessonA);
        $lessonCollection->add($lessonB);

        $userA->setLessonCollection($lessonCollection);
        $course->setLessonCollection($lessonCollection);

        $enrollmentCollection = new ArrayCollection;
        $enrollmentCollection->add($enrollment);
        $userA->setEnrollmentCollection($enrollmentCollection);

        $progressA = new Progress;
        $progressA->setPercent(100);
        $progressA->setUser($userA);
        $progressA->setLesson($lessonA);
        $progressA->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

        $this->getEntityManager()->persist($userA);
        $this->getEntityManager()->persist($teacher);
        $this->getEntityManager()->persist($enrollment);
        $this->getEntityManager()->persist($progressA);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($userA), 1);
        $enrollments = $savedUser->getEnrollmentCollection();

        //deve ser nulo porque o progresso da segunda lição não é 100
        $this->assertNull($enrollments->first()->getCertificationCode());

        $progressB = new Progress;
        $progressB->setPercent(100);
        $progressB->setUser($userA);
        $progressB->setLesson($lessonB);
        $progressB->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

        $this->getEntityManager()->persist($progressB);
        $this->getEntityManager()->flush();
        $savedUser = $this->getEntityManager()->find(get_class($userA), 1);
        $enrollments = $savedUser->getEnrollmentCollection();
        $certificationCode = md5($userA->getId() . $course->getId());

        //deve ser diferente de nulo
        $this->assertEquals($certificationCode, $enrollments->first()->getCertificationCode());
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
}