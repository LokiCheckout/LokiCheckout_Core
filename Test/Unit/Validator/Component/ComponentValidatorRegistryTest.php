<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Validator\Component;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorRegistry;

class ComponentValidatorRegistryTest extends TestCase
{
    public function testGetSelectedValidators()
    {
        $validator1 = $this->createMock(FieldValidatorInterface::class);
        $validator2 = $this->createMock(FieldValidatorInterface::class);

        $validatorRegistry = new FieldValidatorRegistry(['item1' => $validator1, 'item2' => $validator2]);
        $this->assertCount(2, $validatorRegistry->getApplicableValidators(['item1', 'item2', 'item3']));
    }
}
