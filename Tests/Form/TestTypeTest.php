<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Form;

use Loevgaard\DandomainAltapayBundle\Form\TestType;
use Symfony\Component\Form\Test\TypeTestCase;

class TestTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'APIkey' => 'key',
            'APIMerchant' => 'merchant',
        ];

        $form = $this->factory->create(TestType::class);

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
