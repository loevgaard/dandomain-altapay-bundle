<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

class PaymentRepository extends EntityRepository
{
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
     * @return array|Payment[]
     */
    public function findByIds(array $ids) : array
    {
        $qb = $this->getQueryBuilder('p');
        $qb->where($qb->expr()->in('p.id', ':ids'));
        $qb->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }
}
