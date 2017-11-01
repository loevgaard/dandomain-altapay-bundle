<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="dandomain_altapay_http_transactions")
 * @ORM\Entity()
 */
class HttpTransaction
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    protected $request;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    protected $response;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return HttpTransaction
     */
    public function setId(int $id) : HttpTransaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return HttpTransaction
     */
    public function setIp(string $ip): HttpTransaction
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * @param $request
     * @return HttpTransaction
     */
    public function setRequest($request): HttpTransaction
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
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return HttpTransaction
     */
    public function setResponse(string $response): HttpTransaction
    {
        $this->response = $response;

        return $this;
    }
}
