<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

class WebhookExchangeRepository extends EntityRepository
{
    /**
     * @param string $url
     *
     * @return WebhookExchange|null
     */
    public function findByUrl(string $url): ?WebhookExchange
    {
        /** @var WebhookExchange $obj */
        $obj = $this->findOneBy([
            'url' => $url,
        ]);

        return $obj;
    }
}
