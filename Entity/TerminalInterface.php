<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

interface TerminalInterface
{
    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     * @return TerminalInterface
     */
    public function setTitle(string $title): self;

    /**
     * @return string
     */
    public function getCanonicalTitle(): ?string;

    /**
     * @param string $canonicalTitle
     * @return TerminalInterface
     */
    public function setCanonicalTitle(string $canonicalTitle): self;

    /**
     * @return string
     */
    public function getCountry(): ?string;

    /**
     * @param string $country
     * @return TerminalInterface
     */
    public function setCountry(string $country): self;

    /**
     * @return array
     */
    public function getNatures(): ?array;

    /**
     * @param array $natures
     * @return TerminalInterface
     */
    public function setNatures(array $natures): self;

    /**
     * @return array
     */
    public function getCurrencies(): ?array;

    /**
     * @param array $currencies
     * @return TerminalInterface
     */
    public function setCurrencies(array $currencies): self;
}