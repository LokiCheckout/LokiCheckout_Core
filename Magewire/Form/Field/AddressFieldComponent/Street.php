<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;

class Street extends AddressFieldComponent implements FormTextMultipleFieldInterface
{
    protected int $counter = 0;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function setCounter($counter = 0): void
    {
        $this->counter = $counter;
    }

    public function getCount(): int
    {
        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        if ($streetLines > 1) {
            return $streetLines;
        }

        return 1;
    }
}
