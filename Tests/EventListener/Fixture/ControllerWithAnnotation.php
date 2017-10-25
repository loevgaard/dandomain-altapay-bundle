<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\EventListener\Fixture;

use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;

class ControllerWithAnnotation
{
    /**
     * @LogHttpTransaction()
     */
    public function foobarAction()
    {

    }
}
