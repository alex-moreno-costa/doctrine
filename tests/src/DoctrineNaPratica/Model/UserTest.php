<?php

namespace DoctrineNaPratica\Model;

use DoctrineNaPratica\Test\TestCase;
use DoctrineNaPratica\Model\User;
use DoctrineNaPratica\Model\Subscription;
use DoctrineNaPratica\Model\Enrollment;
use DoctrineNaPratica\Model\Course;
use DoctrineNaPratica\Model\Lesson;
use DoctrineNaPratica\Model\GithubProfile;
use DoctrineNaPratica\Model\FacebookProfile;
use DoctrineNaPratica\Model\TwitterProfile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class UserTest
 * @package DoctrineNaPratica\Model
 * @group Model
 */
class UserTest extends TestCase
{
    private function buildUser()
    {
        $user = new User();
        $user->setName('Steve Jobs')
            ->setLogin('steve')
            ->setEmail('steve@apple.com')
            ->setAvatar('steve.png');

        return $user;
    }

    private function buildCourse($courseName)
    {
        $teacher = $this->buildUser();
        $teacher->setLogin('jobs'.$courseName);

        $course = new Course();
        $course->setName($courseName)
            ->setDescription('Curso de PHP')
            ->setValue(100)
            ->setTeacher($teacher);

        return $course;
    }

    public function testUser()
    {
        $user = $this->buildUser();

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());

        $saveduser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertInstanceOf(get_class($user), $saveduser);
        $this->assertEquals($user->getName(), $saveduser->getName());
    }

    public function testUserSubscription()
    {
        $user = $this->buildUser();

        $subscription = new Subscription();
        $subscription->setStatus(1)
            ->setStarted(new \DateTime('now'));

        $user->setSubscription($subscription);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->assertNotNull($subscription->getId());
        $this->assertEquals(1, $subscription->getId());

        $saveduser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertInstanceOf(get_class($subscription), $saveduser->getSubscription());
        $this->assertEquals($subscription->getId(), $saveduser->getSubscription()->getId());
    }

    public function testUserEnrollment()
    {
        $user = $this->buildUser();

        $courseA = $this->buildCourse('PHP');
        $courseB = $this->buildCourse('Doctrine');

        $enrollmentA = new Enrollment();
        $enrollmentA->setUser($user);
        $enrollmentA->setCourse($courseA);

        $enrollmentB = new Enrollment();
        $enrollmentB->setUser($user);
        $enrollmentB->setCourse($courseB);

        $enrollmentCollection = new ArrayCollection();
        $enrollmentCollection->add($enrollmentA);
        $enrollmentCollection->add($enrollmentB);

        $user->setEnrollmentCollection($enrollmentCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $saveduser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertEquals(2, count($saveduser->getEnrollmentCollection()));
    }

    public function testUserCourse()
    {
        $user = $this->buildUser();

        $courseA = new Course();
        $courseA->setName('PHP')
            ->setDescription('Curso de PHP')
            ->setValue(100)
            ->setTeacher($user);

        $courseB = new Course();
        $courseB->setName('Doctrine')
            ->setDescription('Curso de Doctrine')
            ->setValue(400)
            ->setTeacher($user);

        $courseCollection = new ArrayCollection();
        $courseCollection->add($courseA);
        $courseCollection->add($courseB);

        $user->setCourseCollection($courseCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertEquals(2, count($savedUser->getCourseCollection()));

        $savedCourses = $savedUser->getCourseCollection();
        $this->assertEquals('Doctrine', $savedCourses[1]->getName());
    }

    public function testUserLesson()
    {
        $user = $this->buildUser();

        $course = new Course();
        $course->setName('PHP')
            ->setDescription('Curso de PHP')
            ->setValue(100)
            ->setTeacher($user);

        $lessonA = new Lesson();
        $lessonA->setName('Arrays');
        $lessonA->setDescription('Aula sobre arrays');
        $lessonA->setCourse($course);

        $lessonB = new Lesson();
        $lessonB->setName('Datas');
        $lessonB->setDescription('Aula sobre datas');
        $lessonB->setCourse($course);

        $lessonCollection = new ArrayCollection();
        $lessonCollection->add($lessonA);
        $lessonCollection->add($lessonB);

        $user->setLessonCollection($lessonCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), $user->getId());

        $this->assertEquals(2, count($savedUser->getLessonCollection()));

        $savedLessons = $savedUser->getLessonCollection();
        $this->assertEquals(1, $savedLessons[0]->getId());
    }

    public function testUserProfile()
    {
        $user = $this->buildUser();

        $github = new GithubProfile();
        $github->setName('Elton Minetto');
        $github->setLogin('eminetto');
        $github->setEmail('eminetto@coderockr.com');
        $github->setAvatar('eminetto.png');

        $facebook = new FacebookProfile();
        $facebook->setName('Elton Luis Minetto');
        $facebook->setLogin('eminetto');
        $facebook->setEmail('eminetto@coderockr.com');
        $facebook->setAvatar('eminetto.png');

        $twitter = new TwitterProfile();
        $twitter->setName('Elton Minetto');
        $twitter->setLogin('eminetto');
        $twitter->setEmail('eminetto@coderockr.com');
        $twitter->setAvatar('eminetto.png');

        $profileCollection = new ArrayCollection();
        $profileCollection->add($github);
        $profileCollection->add($facebook);
        $profileCollection->add($twitter);

        $user->setProfileCollection($profileCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), 1);
        $this->assertEquals(3, count($savedUser->getProfileCollection()));
        $savedProfiles = $savedUser->getProfileCollection();

        $this->assertInstanceOf(get_class($github), $savedProfiles[0]);
        $this->assertInstanceOf(get_class($facebook), $savedProfiles[1]);
        $this->assertInstanceOf(get_class($twitter), $savedProfiles[2]);
    }
}