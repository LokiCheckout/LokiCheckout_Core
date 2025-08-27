<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Generic;

use LokiCheckout\Core\Component\Base\Behaviour\StepViewModelBehaviour;
use Loki\Field\Component\Base\Field\FieldViewModel;

/**
 * @method CheckoutContext getContext()
 */
class CheckoutViewModel extends FieldViewModel
{
    use StepViewModelBehaviour;

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutComponent';
    }
}
