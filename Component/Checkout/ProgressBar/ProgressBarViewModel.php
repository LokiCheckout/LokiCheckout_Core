<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method ProgressBarContext getContext()
 */
class ProgressBarViewModel extends ComponentViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutSteps';
    }
}
