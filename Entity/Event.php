<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="dandomain_altapay_events")
 * @ORM\Entity()
 */
class Event
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="json")
     */
    protected $body;

    public function __construct(string $name, string $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Event
     */
    public function setId(int $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Event
     */
    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Event
     */
    public function setBody(string $body) : self
    {
        $this->body = $body;
        return $this;
    }
}
