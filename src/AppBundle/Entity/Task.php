<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false, unique=false)
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=50)
     */
    private $title;

    /**
     * @var bool
     * @Assert\NotNull
     * @ORM\Column(name="done", type="boolean", nullable=false, unique=false)
     */
    private $done;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     */
    private $updateAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set done
     *
     * @param boolean $done
     *
     * @return Task
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return bool
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set createAt
     * 
     * @ORM\PrePersist()
     * 
     * @param \DateTime $createAt
     *
     * @return Task
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate
     * 
     * @param \DateTime $updateAt
     *
     * @return Task
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}
