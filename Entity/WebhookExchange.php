<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * We use the terminology from Message Queues where an exchange is where we publish events.
 *
 * @ORM\Table(name="dandomain_altapay_webhook_exchanges")
 * @ORM\Entity()
 * @UniqueEntity("url")
 */
class WebhookExchange
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * This will hold the URL to where the events are published.
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     * @Assert\Url()
     *
     * @ORM\Column(type="string", unique=true, length=191)
     */
    private $url;

    /**
     * This will hold the last event id published.
     *
     * @var int
     *
     * @Assert\GreaterThanOrEqual(0)
     *
     * @ORM\Column(type="integer")
     */
    private $lastEventId;

    /**
     * @var Collection|WebhookQueueItem[]
     *
     * @ORM\OneToMany(targetEntity="WebhookQueueItem", mappedBy="webhookExchange", cascade={"persist", "remove"})
     */
    private $webhookQueueItems;

    public function __construct(string $url, int $lastEventId = 0)
    {
        $this->setUrl($url);
        $this->setLastEventId($lastEventId);
        $this->webhookQueueItems = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @param int $id
     *
     * @return WebhookExchange
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return WebhookExchange
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastEventId(): int
    {
        return $this->lastEventId;
    }

    /**
     * @param int $lastEventId
     *
     * @return WebhookExchange
     */
    public function setLastEventId(int $lastEventId): self
    {
        $this->lastEventId = $lastEventId;

        return $this;
    }

    /**
     * @return WebhookQueueItem[]|Collection
     */
    public function getWebhookQueueItems() : Collection
    {
        return $this->webhookQueueItems;
    }

    /**
     * @param ArrayCollection $webhookQueueItems
     *
     * @return WebhookExchange
     */
    public function setWebhookQueueItems(ArrayCollection $webhookQueueItems)
    {
        $this->webhookQueueItems = $webhookQueueItems;

        return $this;
    }

    /**
     * @param WebhookQueueItem $webhookQueueItem
     *
     * @return WebhookExchange
     */
    public function addWebhookQueueItem(WebhookQueueItem $webhookQueueItem): self
    {
        $webhookQueueItem->setWebhookExchange($this);
        $this->webhookQueueItems->add($webhookQueueItem);

        return $this;
    }
}
