<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\PaymentMethod;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class Banktransfer implements ArgumentInterface
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getInstructions(): string
    {
        return (string)$this->scopeConfig->getValue('payment/banktransfer/instructions');
    }
}
