<?php

namespace DoctrineNaPratica\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class User
 * @package DoctrineNaPratica\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     * @var string
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $avatar;

    /**
     * @ORM\OneToOne(targetEntity="Subscription", mappedBy="user", cascade={"all"})
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity="Enrollment", mappedBy="user", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    private $enrollmentCollection;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="teacher", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    private $courseCollection;

    /**
     * @ORM\ManyToMany(targetEntity="Lesson", inversedBy="userLessons", cascade={"all"})
     * @ORM\JoinTable(name="LessonUser",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="lesson_id", referencedColumnName="id")}
     * )
     */
    private $lessonCollection;

    /**
     * @ORM\OneToMany(targetEntity="Profile", mappedBy="user", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $profileCollection;

    public function __construct()
    {
        $this->courseCollection = new ArrayCollection();
        $this->lessonCollection = new ArrayCollection();
        $this->enrollmentCollection = new ArrayCollection();
        $this->profileCollection = new ArrayCollection();
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
     * @return User
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
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param mixed $subscription
     * @return User
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
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
     * @return User
     */
    public function setEnrollmentCollection($enrollmentCollection)
    {
        $this->enrollmentCollection = $enrollmentCollection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCourseCollection()
    {
        return $this->courseCollection;
    }

    /**
     * @param mixed $courseCollection
     * @return User
     */
    public function setCourseCollection($courseCollection)
    {
        $this->courseCollection = $courseCollection;
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
     * @return User
     */
    public function setLessonCollection($lessonCollection)
    {
        $this->lessonCollection = $lessonCollection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfileCollection()
    {
        return $this->profileCollection;
    }

    /**
     * @param mixed $profileCollection
     * @return User
     */
    public function setProfileCollection($profileCollection)
    {
        $this->profileCollection = $profileCollection;
        return $this;
    }
}