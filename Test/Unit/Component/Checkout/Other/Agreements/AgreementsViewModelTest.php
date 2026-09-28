<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Component\Checkout\Other\Agreements;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Checkout\Other\Agreements\AgreementsViewModel;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AgreementsViewModelTest extends TestCase
{
    public function testGetStepIsExposedAsJsProperty(): void
    {
        $method = new ReflectionMethod(AgreementsViewModel::class, 'getStep');
        $jsPropertyNames = array_map(
            fn ($attribute) => $attribute->newInstance()->name,
            $method->getAttributes(JsProperty::class)
        );

        $this->assertSame(['step'], $jsPropertyNames);
    }
}
