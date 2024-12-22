<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Street2;

use Yireo\LokiCheckout\Component\Checkout\Address\StreetLine\ViewModel as ParentViewModel;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

class ViewModel extends ParentViewModel
{
    use OptionsBehaviour;

    protected int $index = 2;
}
