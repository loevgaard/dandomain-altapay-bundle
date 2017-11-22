<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentRepository;
use PHPUnit\Framework\TestCase;

class PaymentRepositoryTest extends TestCase
{
    public function testReturnNull()
    {
        $paymentRepository = $this->getPaymentRepository();

        $paymentRepository
            ->method('findOneBy')
            ->willReturn(null);

        $this->assertSame(null, $paymentRepository->findByOrderIdOrAltapayId(1));
    }

    public function testFindBySetting()
    {
        $paymentRepository = $this->getPaymentRepository();

        $obj = new Payment();
        $paymentRepository
            ->method('findOneBy')
            ->willReturn($obj);

        $this->assertSame($obj, $paymentRepository->findByOrderIdOrAltapayId(1));
    }

    /**
     * @return PaymentRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getPaymentRepository()
    {
        /** @var PaymentRepository|\PHPUnit_Framework_MockObject_MockObject $paymentRepository */
        $paymentRepository = $this->getMockBuilder(PaymentRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        return $paymentRepository;
    }
}
