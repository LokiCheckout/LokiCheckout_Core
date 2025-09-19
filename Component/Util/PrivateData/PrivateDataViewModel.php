<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Util\PrivateData;

use Loki\Components\Component\ComponentViewModel;
use LokiCheckout\Core\CustomerData\Checkout;

class PrivateDataViewModel extends ComponentViewModel
{
    public function __construct(
        private Checkout $checkoutSection
    ) {
    }

    public function getJsComponentName(): string
    {
        return 'LokiCheckoutPrivateData';
    }

    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'privateData' => $this->checkoutSection->getSectionData(),
        ];
    }
}
