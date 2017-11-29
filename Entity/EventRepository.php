<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Loevgaard\DandomainAltapayBundle\Event\EventInterface;

class EventRepository extends EntityRepository
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator, string $class, SerializerInterface $serializer)
    {
        parent::__construct($managerRegistry, $paginator, $class);

        $this->serializer = $serializer;
    }

    /**
     * @param EventInterface $event
     * @return Event
     */
    public function createFromDomainEvent(EventInterface $event) : Event
    {
        return new Event($event->getName(), $this->serializer->serialize($event, 'json'));
    }

    /**
     * @param int $offsetEventId
     * @param int $limit
     *
     * @return Event[]|null
     */
    public function findRecentEvents(int $offsetEventId, int $limit = 0): ?array
    {
        $qb = $this->getQueryBuilder('e');
        $qb
            ->where($qb->expr()->gt('e.id', $offsetEventId))
            ->orderBy('e.id')
        ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
