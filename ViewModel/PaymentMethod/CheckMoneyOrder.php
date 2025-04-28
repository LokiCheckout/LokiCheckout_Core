<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\PaymentMethod;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class CheckMoneyOrder implements ArgumentInterface
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    public function getPayableTo(): string
    {
        return (string)$this->scopeConfig->getValue('payment/checkmo/payable_to');
    }

    public function getMailingAddress(): string
    {
        return (string)$this->scopeConfig->getValue('payment/checkmo/mailing_address');
    }
}
