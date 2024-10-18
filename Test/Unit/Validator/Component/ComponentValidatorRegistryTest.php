<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Validator\Component;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorRegistry;

class AddressFieldValidatorRegistryTest extends TestCase
{
    public function testGetSelectedValidators()
    {
        $validatorRegistry = new AddressFieldValidatorRegistry(['item1' => 'validator1', 'item2' => 'validator2']);
        $this->assertCount(2, $validatorRegistry->getSelectedValidators(['item1', 'item2', 'item3']));
    }
}
