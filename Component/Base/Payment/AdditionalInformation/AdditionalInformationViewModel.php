<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Payment\AdditionalInformation;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

abstract class AdditionalInformationViewModel extends CheckoutViewModel
{
    protected bool $isRequiredByDefault = true;
}
