<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;
use Yireo\LokiCheckout\Step\StepNavigator;

class CurrentCheckoutStepFixture implements DataFixtureInterface
{
    public function __construct(
        private StepNavigator $stepNavigator
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        $step = $data['step'];
        try {
            $this->stepNavigator->setCurrentStepByCode($step);
        } catch (\Exception $exception){
            echo get_class($exception).": ".$exception->getMessage()."\n";
        }

        return null;
    }
}
