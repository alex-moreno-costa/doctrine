<?php

namespace DoctrineNaPratica\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Course
 * @package DoctrineNaPratica\Model
 * @ORM\Entity
 * @ORM\Table(name="Course")
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $permalink;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2) 49
     * @var float
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courseCollection", cascade={"persist", "merge", "refresh"})
     * @var User
     */
    protected $teacher;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="course", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $lessonCollection;

    /**
     * @ORM\OneToMany(targetEntity="Enrollment", mappedBy="course", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    private $enrollmentCollection;

    public function __construct()
    {
        $this->enrollmentCollection = new ArrayCollection();
        $this->lessonCollection = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Course
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param string $video
     * @return Course
     */
    public function setVideo($video)
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     * @return Course
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return Course
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return User
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param User $teacher
     * @return Course
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLessonCollection()
    {
        return $this->lessonCollection;
    }

    /**
     * @param mixed $lessonCollection
     * @return Course
     */
    public function setLessonCollection($lessonCollection)
    {
        $this->lessonCollection = $lessonCollection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnrollmentCollection()
    {
        return $this->enrollmentCollection;
    }

    /**
     * @param mixed $enrollmentCollection
     * @return Course
     */
    public function setEnrollmentCollection($enrollmentCollection)
    {
        $this->enrollmentCollection = $enrollmentCollection;
        return $this;
    }
}