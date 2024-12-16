<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Generic;

use Yireo\LokiCheckout\Magewire\Checkout\Shipping\ShippingAddress\Prefix;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

class ParentBehaviourTest extends LokiTestCase
{
    public function testComponentWithMaxLength()
    {
        $component = $this->getDummyComponentInstance(Prefix::class);
        $component->getParent()->setData('max_length', 3);
        $component->value = 'toolong';
        $component->validate();
        $this->assertMagewireComponentHasErrors($component);

        $component->clearErrors();
        $component->getParent()->setData('max_length', 8);
        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }

    public function testComponentWithMinLength()
    {
        $component = $this->getDummyComponentInstance(Prefix::class);
        $component->getParent()->setData('min_length', 9);
        $component->value = 'tooshort';
        $component->validate();
        $this->assertMagewireComponentHasErrors($component);

        $component->clearErrors();
        $component->getParent()->setData('min_length', 8);
        $component->validate();
        $this->assertMagewireComponentHasNoErrors($component);
    }
}
