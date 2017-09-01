<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

interface TerminalInterface
{
    /**
     * Returns terminal id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Sets the terminal id.
     *
     * @param mixed $id
     *
     * @return TerminalInterface
     */
    public function setId($id): self;

    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     *
     * @return TerminalInterface
     */
    public function setTitle(string $title): self;

    /**
     * @return string
     */
    public function getSlug(): ?string;

    /**
     * @param string $canonicalTitle
     *
     * @return TerminalInterface
     */
    public function setSlug(string $canonicalTitle): self;

    /**
     * @return string
     */
    public function getCountry(): ?string;

    /**
     * @param string $country
     *
     * @return TerminalInterface
     */
    public function setCountry(string $country): self;

    /**
     * @return array
     */
    public function getNatures(): ?array;

    /**
     * @param array $natures
     *
     * @return TerminalInterface
     */
    public function setNatures(array $natures): self;

    /**
     * @return array
     */
    public function getCurrencies(): ?array;

    /**
     * @param array $currencies
     *
     * @return TerminalInterface
     */
    public function setCurrencies(array $currencies): self;
}
