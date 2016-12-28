<?php

namespace DoctrineNaPratica\Model\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use DoctrineNaPratica\Model\Course as ModelCourse;
use DoctrineNaPratica\Model\Lesson;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cria cursos para popular a base
 */
class Course implements FixtureInterface
{
    private $data = array(
        0 => array(
            'name' => 'Doctrine na prática',
            'value' => 666,
            'teacher' => array(
                'login' => 'eminetto',
            ),
            'lessons' => array(
                0 => array(
                    'name' => 'Introdução',
                    'description' => 'Introdução ao Doctrine',
                ),
                1 => array(
                    'name' => 'Instalação',
                    'description' => 'Instalando o Doctrine',
                ),
            )
        ),
        1 => array(
            'name' => 'Zend Framework 2 na prática',
            'value' => 666,
            'teacher' => array(
                'login' => 'eminetto',
            ),
            'lessons' => array(
                0 => array(
                    'name' => 'Introdução',
                    'description' => 'Introdução ao ZF2',
                ),
                1 => array(
                    'name' => 'Instalação',
                    'description' => 'Instalando o ZF2',
                ),
            )
        ),
    );

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $d) {
            $course = new ModelCourse;
            $course->setName($d['name']);
            $course->setValue($d['value']);

            $teacher = $manager->getRepository('DoctrineNaPratica\Model\User')
                ->findOneBy(array('login' => $d['teacher']['login']));

            $course->setTeacher($teacher);

            $lessons = new ArrayCollection;
            foreach ($d['lessons'] as $l) {
                $lesson = new Lesson;
                $lesson->setName($l['name']);
                $lesson->setDescription($l['description']);
                $lesson->setCourse($course);
                $lessons->add($lesson);
            }

            $course->setLessonCollection($lessons);
            $manager->persist($course);
        }

        $manager->flush();
    }
}