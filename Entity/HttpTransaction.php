<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\MappedSuperclass
 */
abstract class HttpTransaction implements HttpTransactionInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $request;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * {@inheritdoc}
     */
    public function setIp(string $ip): HttpTransactionInterface
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest($request): HttpTransactionInterface
    {
        if ($request instanceof Request) {
            $this->ip = $request->getClientIp();

            $post = '';

            foreach ($request->request->all() as $key => $val) {
                $post .= '&'.$key.'='.$val."\r\n";
            }
            $post = trim($post, '&');

            $request = sprintf('%s %s %s',
                    $request->getMethod(),
                    $request->getRequestUri(),
                    $request->server->get('SERVER_PROTOCOL'))."\r\n".$request->headers."\r\n".$post;
        }

        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(string $response): HttpTransactionInterface
    {
        $this->response = $response;

        return $this;
    }
}
