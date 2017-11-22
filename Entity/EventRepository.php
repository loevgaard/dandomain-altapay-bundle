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

    public function saveEvent(EventInterface $event)
    {
        $event = new Event(get_class($event), $this->serializer->serialize($event, 'json'));
        $this->save($event);
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
