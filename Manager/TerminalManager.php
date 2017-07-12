<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;

class TerminalManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, string $class)
    {
        $this->class = $class;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    public function getClass() : string
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }
        return $this->class;
    }

    /**
     * @param string $slug
     * @return TerminalInterface|null
     */
    public function findTerminalBySlug(string $slug) : ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'slug' => $slug
        ]);

        return $terminal;
    }
}