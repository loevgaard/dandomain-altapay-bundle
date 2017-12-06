<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Synchronizer;

use Loevgaard\AltaPay\Client\Client;
use Loevgaard\AltaPay\Response\GetTerminals;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalRepository;
use Loevgaard\DandomainAltapayBundle\Synchronizer\TerminalSynchronizer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TerminalSynchronizerTest extends TestCase
{
    public function testSyncAll()
    {
        $xml = <<<XML
<?xml version="1.0"?>
<APIResponse version="20170228">
  <Header>
    <Date>2016-01-07T23:43:20+01:00</Date>
    <Path>API/getTerminals</Path>
    <ErrorCode>0</ErrorCode>
    <ErrorMessage></ErrorMessage>
  </Header>
  <Body>
    <Result>Success</Result>
    <Terminals>
      <Terminal>
        <Title>AltaPay Multi-Nature Terminal</Title>
        <Country>DK</Country>
        <Natures>
          <Nature>CreditCard</Nature>
          <Nature>EPayment</Nature>
          <Nature>IdealPayment</Nature>
          <Nature>Invoice</Nature>
        </Natures>
        <Currencies>
          <Currency>DKK</Currency>
          <Currency>EUR</Currency>
        </Currencies>
      </Terminal>
      <Terminal>
        <Title>AltaPay BankPayment Terminal</Title>
        <Country></Country>
        <Natures>
          <Nature>BankPayment</Nature>
        </Natures>
        <Currencies>
          <Currency>EUR</Currency>
        </Currencies>
      </Terminal>
    </Terminals>
  </Body>
</APIResponse>
XML;

        $psrResponse = $this->createMock(ResponseInterface::class);
        $psrResponse
            ->expects($this->any())
            ->method('getBody')
            ->willReturn($xml)
            ;
        $getTerminalsResponse = new GetTerminals($psrResponse);

        /** @var TerminalRepository|\PHPUnit_Framework_MockObject_MockObject $terminalRepository */
        $terminalRepository = $this->getMockBuilder(TerminalRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'findTerminalByTitle'])
            ->getMock()
        ;

        $terminalRepository
            ->expects($this->any())
            ->method('save')
            ->willReturn(null)
        ;

        $terminalRepository
            ->expects($this->any())
            ->method('findTerminalByTitle')
            ->willReturn(null)
        ;

        $altapayClient = $this->createMock(Client::class);
        $altapayClient
            ->expects($this->any())
            ->method('getTerminals')
            ->willReturn($getTerminalsResponse)
        ;

        $terminalSynchronizer = new TerminalSynchronizer($terminalRepository, $altapayClient);
        $terminalSynchronizer->syncAll();

        $this->assertTrue(true);
    }
}
