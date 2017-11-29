<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

class WebhookExchangeRepository extends EntityRepository
{
    /**
     * This method will find the webhook exchange with the given URL
     * If it doesn't exist it will create it.
     *
     * @param string $url
     *
     * @return WebhookExchange
     */
    public function findByUrlOrCreate(string $url): WebhookExchange
    {
        /** @var WebhookExchange $obj */
        $obj = $this->findOneBy([
            'url' => $url,
        ]);

        if (!$obj) {
            $obj = new WebhookExchange($url);
            $this->save($obj);
        }

        return $obj;
    }
}
