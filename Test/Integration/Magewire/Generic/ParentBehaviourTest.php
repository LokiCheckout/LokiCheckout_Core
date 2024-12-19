<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Generic;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\Prefix;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

class ParentBehaviourTest extends LokiTestCase
{
    public function testComponentWithMaxLength()
    {
        $component = $this->getDummyComponentInstance(Prefix::class);
        $component->getBlock()->setData('max_length', 3);
        $component->value = 'toolong';
        $component->validate();
        $this->assertComponentHasErrors($component);

        $component->clearErrors();
        $component->getBlock()->setData('max_length', 8);
        $component->validate();
        $this->assertComponentHasNoErrors($component);
    }

    public function testComponentWithMinLength()
    {
        $component = $this->getDummyComponentInstance(Prefix::class);
        $component->getBlock()->setData('min_length', 9);
        $component->value = 'tooshort';
        $component->validate();
        $this->assertComponentHasErrors($component);

        $component->clearErrors();
        $component->getBlock()->setData('min_length', 8);
        $component->validate();
        $this->assertComponentHasNoErrors($component);
    }
}
