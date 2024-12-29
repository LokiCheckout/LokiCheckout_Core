<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepForwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

/**
 * @method GenericContext getContext()
 */
class StepForwardButtonViewModel extends GenericViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepForwardButton';
    }
}
