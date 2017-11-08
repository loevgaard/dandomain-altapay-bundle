# Dandomain AltaPay Bundle

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A bundle for coupling a Dandomain payment to Altapay

## Install

Via Composer

```bash
$ composer require loevgaard/dandomain-altapay-bundle
```

### Update AppKernel.php

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
            new FOS\RestBundle\FOSRestBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Tbbc\MoneyBundle\TbbcMoneyBundle(),
        );

        // ...
    }

    // ...
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
# app/config/config.yml
loevgaard_dandomain_altapay:
    altapay_url: https://testgateway.altapaysecure.com
    altapay_username: insert username
    altapay_password: insert password
    shared_key_1: insert shared key 1 from Dandomain
    shared_key_2: insert shared key 2 from Dandomain
    altapay_ips: ['77.66.40.133', '77.66.62.133']
    default_settings:
        layout:
            logo: https://example.com/logo_default.png
    
knp_doctrine_behaviors:
    timestampable: true
    
    
# Enable translator
framework:
    # ...
    translator: { fallbacks: ['%locale%'] }
    # ...
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
