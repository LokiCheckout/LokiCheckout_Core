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
        $shippingCountryId = $this->getShippingCountryId();
        if (empty($shippingCountryId)) {
            return $handles;
        }

        $billingCountryId = $this->getBillingCountryId();
        if (empty($billingCountryId)) {
            $billingCountryId = $shippingCountryId;
        }

        $handles = $this->removeCurrentCountryHandles($handles);

        $handles[] = 'loki_checkout_country_'.$shippingCountryId;
        $handles[] = 'loki_checkout_shipping_address_country_'.$shippingCountryId;
        $handles[] = 'loki_checkout_billing_address_country_'.$billingCountryId;

        foreach ($handles as $handle) {
            if (str_starts_with($handle, 'loki_checkout_skin_')) {
                $handles[] = $handle.'_country_'.$shippingCountryId;
                $handles[] = $handle.'_shipping_address_country_'.$shippingCountryId;
                $handles[] = $handle.'_billing_address_country_'.$billingCountryId;
            }

            if (str_starts_with($handle, 'loki_checkout_address_')) {
                $handles[] = $handle.'_country_'.$shippingCountryId;
                $handles[] = $handle.'_shipping_address_country_'.$shippingCountryId;
                $handles[] = $handle.'_billing_address_country_'.$billingCountryId;
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
