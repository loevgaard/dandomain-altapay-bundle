<?php

namespace Loevgaard\DandomainAltapayBundle\Command;

use GuzzleHttp\Psr7;
use Loevgaard\AltaPay\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingAltapayCommand extends ContainerAwareCommand
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:altapay:ping')
            ->setDescription('This will test the connection to Altapay and test the login')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->client->doRequest('get', '/merchant/API/login');
        $output->write(Psr7\str($response)."\n");
    }
}
