<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Synchronizer;

use Loevgaard\AltaPay\Client;
use Loevgaard\AltaPay\Response\GetTerminals;
use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Loevgaard\DandomainAltapayBundle\Manager\TerminalManager;
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

        $terminal = $this->createMock(Terminal::class);

        $psrResponse = $this->createMock(ResponseInterface::class);
        $psrResponse
            ->expects($this->any())
            ->method('getBody')
            ->willReturn($xml)
            ;
        $getTerminalsResponse = new GetTerminals($psrResponse);

        /** @var TerminalManager|\PHPUnit_Framework_MockObject_MockObject $terminalManager */
        $terminalManager = $this->getMockBuilder(TerminalManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'update', 'findTerminalByTitle'])
            ->getMock()
        ;

        $terminalManager
            ->expects($this->any())
            ->method('create')
            ->willReturn($terminal)
        ;

        $terminalManager
            ->expects($this->any())
            ->method('update')
            ->willReturn(null)
        ;

        $terminalManager
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

        $terminalSynchronizer = new TerminalSynchronizer($terminalManager, $altapayClient);
        $terminalSynchronizer->syncAll();

        $this->assertTrue(true);
    }
}
