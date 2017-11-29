<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Form;

use Loevgaard\DandomainAltapayBundle\Form\SiteSettingType;
use Symfony\Component\Form\Test\TypeTestCase;

class SiteSettingTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'siteId' => '26',
            'setting' => 'setting',
            'val' => 'val',
        ];

        $form = $this->factory->create(SiteSettingType::class);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
