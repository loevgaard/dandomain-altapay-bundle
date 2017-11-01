<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="dandomain_altapay_callbacks",
 *     indexes={@ORM\Index(columns={"slug"})}
 *     )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("slug")
 */
class Terminal
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(name="slug", type="string", unique=true, length=191)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Country()
     *
     * @ORM\Column(type="string")
     */
    protected $country;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $natures;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $currencies;

    /**
     * We only set the canonical title when we persist the object
     * This is because the canonical title is used for URLs and
     * changing this would require the user to change the URL in the
     * Dandomain gateway settings.
     *
     * @ORM\PrePersist
     */
    public function updateSlug()
    {
        $this->slug = (new Slugify())->slugify($this->title);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return Terminal
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Terminal
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Terminal
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Terminal
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getNatures(): ?array
    {
        return $this->natures;
    }

    /**
     * @param array $natures
     *
     * @return Terminal
     */
    public function setNatures(array $natures): self
    {
        $this->natures = $natures;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCurrencies(): ?array
    {
        return $this->currencies;
    }

    /**
     * @param array $currencies
     *
     * @return Terminal
     */
    public function setCurrencies(array $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }
}
