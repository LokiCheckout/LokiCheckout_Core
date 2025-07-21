<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Payment;

use LokiCheckout\Core\ViewModel\CheckoutState;

class ApplePayRequest
{
    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function getMerchantCapabilities(): array
    {
        // @todo: Make this configurable
        return [
            'supports3DS',
            'supportsCredit',
            'supportsDebit',
            'supportsEMV',
        ];
    }

    public function getSupportedNetworks(): array
    {
        // @todo: Make this configurable
        return [
            "amex",
            "maestro",
            "masterCard",
            "visa",
            "vPay",
        ];
    }

    public function getRequiredBillingContactFields(): array
    {
        $requiredBillingContactFields = [];
        $requiredBillingContactFields[] = 'billingAddress';

        if (false === $this->checkoutState->getQuote()->isVirtual()) {
            $requiredBillingContactFields[] = 'postalAddress';
        }

        return $requiredBillingContactFields;
    }

    public function getTotal(): array
    {
        // @todo: Is this only needed with MultiSafepay?
        return [
            "label" => "Your Merchant Name",
            "type" => "final",
            "amount" => $this->checkoutState->getQuote()->getGrandTotal(),
        ];
    }

    public function toJson(): array
    {
        return [
            'countryCode' => $this->getCountryCode(),
            'currencyCode' => $this->getCurrencyCode(),
            'merchantCapabilities' => $this->getMerchantCapabilities(),
            'supportedNetworks' => $this->getSupportedNetworks(),
            'requiredBillingContactFields' => $this->getRequiredBillingContactFields(),
            'total' => $this->getTotal(),
        ];
    }

    private function getCountryCode(): string
    {
        return strtoupper((string)$this->checkoutState->getQuote()->getBillingAddress()->getCountryId());
    }

    private function getCurrencyCode(): string
    {
        $cart = $this->checkoutState->getQuote();
        return $cart->getQuoteCurrencyCode();
    }
}
