<?php

namespace DoctrineNaPratica\Model\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event;
use Doctrine\ORM\Events;
use DoctrineNaPratica\Model\Course;
use DoctrineNaPratica\Model\User;

/**
 * Class EntitySubscriber
 */
class EntitySubscriber implements EventSubscriber
{
    private $listenerdEntities = array(
        'DoctrineNaPratica\Model\Progress',
    );

    public function getSubscribedEvents()
    {
        return array(
            Events::onFlush,
            Events::preUpdate,
            Events::postUpdate,
            Events::postPersist,
        );
    }

    protected function isListenedTo($entity)
    {
        $entityClass = get_class($entity);

        if (in_array($entityClass, $this->listenerdEntities)) {
            return true;
        }

        return false;
    }

    /**
     * @param $course Course
     * @param $user User
     * @param $em EntityManager
     */
    private function getCourseProgress(Course $course, User $user, EntityManager $em)
    {
        $lessons = $user->getLessonCollection();
        $lessonsIds = array();

        foreach ($lessons as $l) {
            $lessonsIds[] = $l->getId();
        }

        $qb = $em->createQueryBuilder();
        $qb->select('SUM(p.percent) AS percent')
            ->from('DoctrineNaPratica\Model\Progress', 'p')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->in('p.lesson', ':lessonsIds'),
                    $qb->expr()->eq('p.user', ':user')
                )
            )
            ->setParameters(array(
                'lessonsIds' => $lessonsIds,
                'user' => $user,
            ));

        $query = $qb->getQuery();
        $percent = $query->getSingleScalarResult();

        return $percent;
    }

    public function postPersist(EventArgs $args)
    {
        //só faz o cálculo caso a entidade sendo salva é a monitorada
        if ( ! $this->isListenedTo($args->getEntity())) return;

        $e = $args->getEntity();
        $em = $args->getEntityManager();
        $course = $e->getLesson()->getCourse();
        $user = $e->getUser();
        $numberOfLessons = count($e->getLesson()->getCourse()->getLessonCollection());
        $courseProgress = $this->getCourseProgress($course, $user, $em);

        //verifica se progresso do aluno no curso é igual ao total do curso

        if (($numberOfLessons * 100) == $courseProgress) {
            //pega o Enrollment do usuário
            $enrollment = $em->getRepository('DoctrineNaPratica\Model\Enrollment')->findOneBy(array('user' => $user, 'course' => $course));

            $certificationCode = md5($user->getId() . $course->getId());
            $enrollment->setCertificationCode($certificationCode);
            $em->persist($enrollment);
            $em->flush();
        }
    }

    public function preUpdate(EventArgs $args)
    {

    }

    public function onFlush(EventArgs $args)
    {

    }

    public function postUpdate(EventArgs $args)
    {

    }
}