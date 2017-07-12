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
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository() : ObjectRepository
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
     * @return TerminalInterface
     */
    public function createTerminal() : TerminalInterface
    {
        $class = $this->getClass();
        $terminal = new $class();
        return $terminal;
    }

    public function deleteTerminal(TerminalInterface $terminal)
    {
        $this->objectManager->remove($terminal);
        $this->objectManager->flush();
    }

    /**
     * @param string $title
     * @return TerminalInterface|null
     */
    public function findTerminalByTitle(string $title) : ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'title' => $title
        ]);

        return $terminal;
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

    /**
     * @param TerminalInterface $terminal
     * @param bool $flush
     */
    public function updateTerminal(TerminalInterface $terminal, bool $flush = true)
    {
        $this->objectManager->persist($terminal);

        if($flush) {
            $this->objectManager->flush();
        }
    }
}