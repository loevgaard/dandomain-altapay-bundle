# Dandomain AltaPay Bundle

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A library for using the Dandomain Pay API to make payments in the Dandomain webshop system

## Install

Via Composer

```bash
$ composer require loevgaard/dandomain-altapay-bundle
```

## Usage

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Loevgaard\DandomainAltapayBundle\LoevgaardDandomainAltapayBundle(),
        );

        // ...
    }

    // ...
}
```

### Doctrine ORM Terminal class
```php
<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal as BaseTerminal;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="terminals")
 */
class Terminal extends BaseTerminal
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

### Import routing
```yaml
# app/config/routing.yml
loevgaard_dandomain_altapay:
    resource: "@LoevgaardDandomainAltapayBundle/Resources/config/routing.yml"
```

### Update config.yml
```yaml
# app/config/routing.yml
loevgaard_dandomain_altapay:
    altapay_username: insert username
    altapay_password: insert password
    shared_key_1: insert shared key 1 from Dandomain
    shared_key_2: insert shared key 2 from Dandomain
    terminal_class: AppBundle\Entity\Terminal
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `joachim@loevgaard.dk` instead of using the issue tracker.

## Credits

- [Joachim Løvgaard][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/loevgaard/dandomain-altapay-bundle.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/loevgaard/dandomain-altapay-bundle/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/loevgaard/dandomain-altapay-bundle.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/loevgaard/dandomain-altapay-bundle.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/loevgaard/dandomain-altapay-bundle.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/loevgaard/dandomain-altapay-bundle
[link-travis]: https://travis-ci.org/loevgaard/dandomain-altapay-bundle
[link-scrutinizer]: https://scrutinizer-ci.com/g/loevgaard/dandomain-altapay-bundle/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/loevgaard/dandomain-altapay-bundle
[link-downloads]: https://packagist.org/packages/loevgaard/dandomain-altapay-bundle
[link-author]: https://github.com/loevgaard
[link-contributors]: ../../contributors
