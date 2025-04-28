<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\PaymentMethod;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class CashOnDelivery implements ArgumentInterface
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getInstructions(): string
    {
        return (string)$this->scopeConfig->getValue('payment/cashondelivery/instructions');
    }
}
