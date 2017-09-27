<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\DandomainAltapayBundle\Entity\HttpTransactionInterface;
use Loevgaard\DoctrineManager\Manager;

class HttpTransactionManager extends Manager
{
    /**
     * @return HttpTransactionInterface
     */
    public function create()
    {
        return parent::create();
    }

    /**
     * @param HttpTransactionInterface $obj
     */
    public function delete($obj)
    {
        parent::delete($obj);
    }

    /**
     * @param HttpTransactionInterface $obj
     * @param bool                     $flush
     */
    public function update($obj, $flush = true)
    {
        parent::update($obj, $flush);
    }
}
