<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextMultipleFieldInterface;

class Street2 extends AddressFieldComponent
{
    protected int $index = 2;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function isVisible(): bool
    {
        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        return $streetLines > $this->index;
    }
}
