<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class Terminal implements TerminalInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
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
     * Dandomain gateway settings
     *
     * @ORM\PrePersist
     */
    public function updateSlug() {
        $this->slug = (new Slugify())->slugify($this->title);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title) : TerminalInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @inheritdoc
     */
    public function setSlug(string $slug) : TerminalInterface
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @inheritdoc
     */
    public function setCountry(string $country) : TerminalInterface
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNatures(): ?array
    {
        return $this->natures;
    }

    /**
     * @inheritdoc
     */
    public function setNatures(array $natures) : TerminalInterface
    {
        $this->natures = $natures;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurrencies(): ?array
    {
        return $this->currencies;
    }

    /**
     * @inheritdoc
     */
    public function setCurrencies(array $currencies) : TerminalInterface
    {
        $this->currencies = $currencies;
        return $this;
    }
}