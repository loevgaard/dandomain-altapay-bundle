<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GuzzleHttp\Psr7;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The webhook queue is where events are inserted/produced and later consumed.
 *
 * @ORM\Table(name="dandomain_altapay_webhook_queue")
 * @ORM\Entity()
 * @UniqueEntity("url")
 */
class WebhookQueueItem
{
    use ORMBehaviors\Timestampable\Timestampable;

    const STATUS_PENDING = 'pending';
    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * This is the content that will be posted to the $url
     * This will be JSON.
     *
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * This will hold a string representation of the request sent to the $url.
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $request;

    /**
     * This will hold a string representation of the response received from the $url.
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $response;

    /**
     * @var int
     *
     * @Assert\GreaterThanOrEqual(0)
     *
     * @ORM\Column(type="integer")
     */
    private $tries;

    /**
     * This is the time when we try to consume this item.
     *
     * @var \DateTimeImmutable
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $nextTry;

    /**
     * @var string
     *
     * @Assert\Choice(callback="getStatuses")
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $error;

    /**
     * @var WebhookExchange
     *
     * @ORM\ManyToOne(targetEntity="WebhookExchange", inversedBy="webhookQueueItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $webhookExchange;

    /**
     * @param string          $content
     * @param WebhookExchange $webhookExchange
     */
    public function __construct(string $content, WebhookExchange $webhookExchange)
    {
        $this->setContent($content);
        $this->setTries(0);
        $this->setNextTry(new \DateTimeImmutable());
        $this->setStatus(self::STATUS_PENDING);
        $this->setWebhookExchange($webhookExchange);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => self::STATUS_PENDING,
            self::STATUS_ERROR => self::STATUS_ERROR,
            self::STATUS_SUCCESS => self::STATUS_SUCCESS,
        ];
    }

    public function markAsSuccess()
    {
        $this->incrementTries();
        $this->setStatus(self::STATUS_SUCCESS);
        $this->setError(null);
    }

    public function markAsError($error)
    {
        if ($error instanceof \Exception) {
            $error = '['.get_class($error).'] '.$error->getMessage().' on line '.$error->getLine().' in file `'.$error->getFile().'`';
        }
        $this->incrementTries();
        $this->setStatus(self::STATUS_ERROR);
        $this->setError($error);
        $this->updateNextTry();
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
     * @return WebhookQueueItem
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return WebhookQueueItem
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRequest(): ?string
    {
        return $this->request;
    }

    /**
     * @param null|string $request
     *
     * @return WebhookQueueItem
     */
    public function setRequest($request): self
    {
        if ($request instanceof RequestInterface) {
            $request = Psr7\str($request);
        }

        $this->request = $request;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface|string $response
     *
     * @return WebhookQueueItem
     */
    public function setResponse($response): self
    {
        if ($response instanceof ResponseInterface) {
            $response = Psr7\str($response);
        }

        $this->response = $response;

        return $this;
    }

    /**
     * @return int
     */
    public function getTries(): int
    {
        return $this->tries;
    }

    /**
     * @param int $tries
     *
     * @return WebhookQueueItem
     */
    public function setTries(int $tries): self
    {
        $this->tries = $tries;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getNextTry(): \DateTimeImmutable
    {
        return $this->nextTry;
    }

    /**
     * @param \DateTimeImmutable $nextTry
     *
     * @return WebhookQueueItem
     */
    public function setNextTry(\DateTimeImmutable $nextTry): self
    {
        $this->nextTry = $nextTry;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return WebhookQueueItem
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns true if the status equals $status.
     *
     * @param string $status
     *
     * @return bool
     */
    public function isStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * @return string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     *
     * @return WebhookQueueItem
     */
    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return WebhookExchange
     */
    public function getWebhookExchange(): WebhookExchange
    {
        return $this->webhookExchange;
    }

    /**
     * @param WebhookExchange $webhookExchange
     *
     * @return WebhookQueueItem
     */
    public function setWebhookExchange(WebhookExchange $webhookExchange): self
    {
        $this->webhookExchange = $webhookExchange;

        return $this;
    }

    private function incrementTries(): self
    {
        ++$this->tries;

        return $this;
    }

    /**
     * This method will compute the next try and set the nextTry property.
     *
     * @return WebhookQueueItem
     */
    private function updateNextTry(): self
    {
        $minutesToWait = 2 ** $this->tries;

        if ($minutesToWait > 120) {
            $minutesToWait = 120;
        }

        $nextTry = \DateTimeImmutable::createFromMutable($this->getUpdatedAt() ?? new \DateTime());
        $nextTry = $nextTry->add(new \DateInterval('PT'.$minutesToWait.'M'));

        $this->setNextTry($nextTry);

        return $this;
    }
}
