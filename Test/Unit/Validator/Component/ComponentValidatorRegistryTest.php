<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Validator\Component;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Filter\FilterInterface;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorRegistry;

class ComponentValidatorRegistryTest extends TestCase
{
    public function testGetSelectedValidators()
    {
        $validator1 = $this->createMock(AddressFieldValidatorInterface::class);
        $validator2 = $this->createMock(AddressFieldValidatorInterface::class);

        $validatorRegistry = new AddressFieldValidatorRegistry(['item1' => $validator1, 'item2' => $validator2]);
        $this->assertCount(2, $validatorRegistry->getSelectedValidators(['item1', 'item2', 'item3']));
    }
}
