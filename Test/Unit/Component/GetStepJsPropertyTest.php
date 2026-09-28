<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Component;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Base\QuoteField\QuoteFieldViewModel;
use LokiCheckout\Core\Component\Checkout\Other\Agreements\AgreementsViewModel;
use LokiCheckout\Core\Component\Checkout\StepButton\AbstractStepButtonViewModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class GetStepJsPropertyTest extends TestCase
{
    #[DataProvider('getViewModelClasses')]
    public function testGetStepIsExposedAsJsProperty(string $viewModelClass): void
    {
        $method = new ReflectionMethod($viewModelClass, 'getStep');
        $jsPropertyNames = array_map(
            fn ($attribute) => $attribute->newInstance()->name,
            $method->getAttributes(JsProperty::class)
        );

        $this->assertSame(['step'], $jsPropertyNames);
    }

    public static function getViewModelClasses(): array
    {
        return [
            [AgreementsViewModel::class],
            [QuoteFieldViewModel::class],
            [AbstractStepButtonViewModel::class],
        ];
    }
}
