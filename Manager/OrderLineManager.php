<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\OrderLineInterface;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;

class OrderLineManager
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

    /**
     * @return string
     */
    public function getClass() : string
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }
        return $this->class;
    }

    /**
     * @return OrderLineInterface
     */
    public function createOrderLine() : OrderLineInterface
    {
        $class = $this->getClass();
        $orderLine = new $class();
        return $orderLine;
    }

    /**
     * @param OrderLineInterface $orderLine
     */
    public function deleteOrderLine(OrderLineInterface $orderLine)
    {
        $this->objectManager->remove($orderLine);
        $this->objectManager->flush();
    }

    /**
     * @param OrderLineInterface $orderLine
     * @param bool $flush
     */
    public function updateOrderLine(OrderLineInterface $orderLine, bool $flush = true)
    {
        $this->objectManager->persist($orderLine);

        if($flush) {
            $this->objectManager->flush();
        }
    }
}