<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method GenericContext getContext()
 */
class ProgressBarViewModel extends ComponentViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutSteps';
    }
}
