<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentRepository extends EntityRepository
{
    /**
     * @var FilterBuilderUpdaterInterface
     */
    protected $filterBuilderUpdater;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator, string $class, FilterBuilderUpdaterInterface $filterBuilderUpdater)
    {
        $this->filterBuilderUpdater = $filterBuilderUpdater;

        parent::__construct($managerRegistry, $paginator, $class);
    }

    /**
     * @param $id
     *
     * @return null|Payment
     */
    public function findByOrderIdOrAltapayId($id): ?Payment
    {
        /** @var Payment $payment */
        $payment = $this->findOneBy([
            'orderId' => $id,
        ]);

        if (!$payment) {
            /** @var Payment $payment */
            $payment = $this->findOneBy([
                'altapayId' => $id,
            ]);
        }

        return $payment;
    }

    /**
     * @param array $ids
     * @return Payment[]
     */
    public function findByIds(array $ids) : array
    {
        $qb = $this->getQueryBuilder('p');
        $qb->where($qb->expr()->in('p.id', ':ids'));
        $qb->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }

    public function findAllWithPagingAndFilter($page = 1, $itemsPerPage = 100, array $orderBy = [], FormInterface $filterForm, Request $request, QueryBuilder $qb = null): PaginationInterface
    {
        if (!$qb) {
            $qb = $this->getQueryBuilder('e');
        }

        if ($request->query->has($filterForm->getName())) {
            // manually bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            // build the query from the given form object
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $qb);
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($field, $direction);
        }

        $objs = $this->paginator->paginate(
            $qb,
            $page,
            $itemsPerPage
        );

        return $objs;
    }
}
