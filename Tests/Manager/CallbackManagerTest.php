<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\CallbackInterface;
use Loevgaard\DandomainAltapayBundle\Manager\CallbackManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CallbackManagerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CallbackManager
     */
    protected $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ObjectRepository
     */
    protected $objectRepository;

    protected function setUp()
    {
        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject $managerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($objectManager);

        $this->objectRepository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->objectRepository);

        $this->manager = new CallbackManager($managerRegistry, 'Loevgaard\DandomainAltapayBundle\Tests\TestCallback');
    }

    public function testCreateCallbackFromRequest()
    {
        $request = new Request([], [
            'shop_orderid' => 123,
            'amount' => 100,
            'currency' => 208,
            'language' => 'language',
            'transaction_info' => ['transaction info'],
            'status' => 'status',
            'error_message' => 'errorMessage',
            'merchant_error_message' => 'merchantErrorMessage',
            'cardholder_message_must_be_shown' => 'cardholderMessageMustBeShown',
            'transaction_id' => 'transactionId',
            'type' => 'type',
            'payment_status' => 'paymentStatus',
            'masked_credit_card' => 'maskedCreditCard',
            'blacklist_token' => 'blacklistToken',
            'credit_card_token' => 'creditCardToken',
            'nature' => 'nature',
            'require_capture' => 'requireCapture',
            'xml' => 'xml',
            'checksum' => 'checksum',
            'fraud_risk_score' => 9.5,
            'fraud_explanation' => 'fraudExplanation',
            'fraud_recommendation' => 'fraudRecommendation',
            'avs_code' => 'avsCode',
            'avs_text' => 'avsText',
        ]);
        $callback = $this->manager->createCallbackFromRequest($request);

        $this->assertInstanceOf(CallbackInterface::class, $callback);
    }
}
