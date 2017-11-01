<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="dandomain_altapay_site_settings",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"site_id", "setting"})},
 *     indexes={@ORM\Index(columns={"site_id"}), @ORM\Index(columns={"setting"})}
 *     )
 * @ORM\Entity()
 */
class SiteSetting
{
    const SETTING_LAYOUT_LOGO = 'layout.logo';

    const SETTING_TRANSLATION_PREFIX = 'site_setting.settings.';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(name="site_id", type="integer")
     */
    protected $siteId;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(name="setting", type="string", length=191)
     */
    protected $setting;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    protected $val;

    public function __toString()
    {
        return (string) $this->val;
    }

    public static function getSettings(): array
    {
        return [
            self::SETTING_LAYOUT_LOGO => self::SETTING_LAYOUT_LOGO,
        ];
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return SiteSetting
     */
    public function setId(int $id): SiteSetting
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     *
     * @return SiteSetting
     */
    public function setSiteId(int $siteId): SiteSetting
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSetting(): ?string
    {
        return $this->setting;
    }

    /**
     * @param string $setting
     *
     * @return SiteSetting
     */
    public function setSetting(string $setting): SiteSetting
    {
        $this->setting = $setting;

        return $this;
    }

    /**
     * @return string
     */
    public function getVal(): ?string
    {
        return $this->val;
    }

    /**
     * @param string $val
     *
     * @return SiteSetting
     */
    public function setVal(string $val): SiteSetting
    {
        $this->val = $val;

        return $this;
    }
}
