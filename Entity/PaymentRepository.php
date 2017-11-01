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
}
