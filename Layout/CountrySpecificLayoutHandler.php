<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Layout;

use Loki\Components\Exception\RedirectException;
use Loki\Components\Layout\LayoutHandlerInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;

class CountrySpecificLayoutHandler implements LayoutHandlerInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function get(array $handles): array
    {
        $shippingCountryId = $this->isVirtual() ? '' : $this->getShippingCountryId();
        $billingCountryId = $this->getBillingCountryId();
        if (empty($shippingCountryId) && empty($billingCountryId)) {
            return $handles;
        }

        if (empty($billingCountryId)) {
            $billingCountryId = $shippingCountryId;
        }

        $countryId = empty($shippingCountryId) ? $billingCountryId : $shippingCountryId;

        $handles = $this->removeCurrentCountryHandles($handles);

        $suffixes = ['_country_'.$countryId];
        if (!empty($shippingCountryId)) {
            $suffixes[] = '_shipping_address_country_'.$shippingCountryId;
        }

        $suffixes[] = '_billing_address_country_'.$billingCountryId;

        foreach ($suffixes as $suffix) {
            $handles[] = 'loki_checkout'.$suffix;
        }

        foreach ($handles as $handle) {
            if (str_starts_with($handle, 'loki_checkout_skin_') || str_starts_with($handle, 'loki_checkout_address_')) {
                foreach ($suffixes as $suffix) {
                    $handles[] = $handle.$suffix;
                }
            }
        }

        return $handles;
    }

    private function removeCurrentCountryHandles(array $handles): array
    {
        foreach ($handles as $handleId => $handle) {
            if (preg_match('/country_([a-z]{2})(?![a-z])/', $handle)) {
                unset($handles[$handleId]);
            }
        }

        return $handles;
    }

    private function isVirtual(): bool
    {
        try {
            return (bool)$this->checkoutState->getQuote()->isVirtual();
        } catch (RedirectException $redirectException) {
            return false;
        }
    }

    private function getShippingCountryId(): string
    {
        try {
            return strtolower((string)$this->checkoutState->getQuote()->getShippingAddress()->getCountryId());
        } catch (RedirectException $redirectException) {
            return '';
        }
    }

    private function getBillingCountryId(): string
    {
        try {
            return strtolower((string)$this->checkoutState->getQuote()->getBillingAddress()->getCountryId());
        } catch (RedirectException $redirectException) {
            return '';
        }
    }
}
