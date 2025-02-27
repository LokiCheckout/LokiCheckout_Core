<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Yireo\LokiCheckout\Component\Base\Behaviour\StepViewModelBehaviour;
use Yireo\LokiFieldComponents\Component\Base\Field\FieldViewModel;

/**
 * @method CheckoutContext getContext()
 */
class CheckoutViewModel extends FieldViewModel
{
    use StepViewModelBehaviour;

    public function getFieldLabel(): string
    {
        return '';
    }
}
