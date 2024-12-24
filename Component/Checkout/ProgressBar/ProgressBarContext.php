<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ProgressBarContext implements ComponentContextInterface
{
    public function __construct(
        public StepNavigator $stepNavigator
    ) {
    }
}
