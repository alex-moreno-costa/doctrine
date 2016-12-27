<?php

namespace DoctrineNaPratica\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Subscription
 * @package DoctrineNaPratica\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="subscription")
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="subscription")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @var integer
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $started;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $finished;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Subscription
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Subscription
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Subscription
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param \DateTime $started
     * @return Subscription
     */
    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * @param \DateTime $finished
     * @return Subscription
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
        return $this;
    }
}