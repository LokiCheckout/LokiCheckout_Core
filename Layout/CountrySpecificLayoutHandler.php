<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Layout;

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
        $countryId = $this->getCountryId();
        if (empty($countryId)) {
            return $handles;
        }

        foreach ($handles as $handleId => $handle) {
            if (preg_match('/country_([a-z]{2})/', $handle)) {
                unset($handles[$handleId]);
            }
        }

        $handles[] = 'loki_checkout_country_'.$countryId;

        foreach ($handles as $handle) {
            if (str_starts_with($handle, 'loki_checkout_skin_')) {
                $handles[] = $handle.'_country_'.$countryId;
            }

            if (str_starts_with($handle, 'loki_checkout_address_')) {
                $handles[] = $handle.'_country_'.$countryId;
            }
        }

        return $handles;
    }

    private function getCountryId(): string
    {
        return strtolower((string)$this->checkoutState->getQuote()->getShippingAddress()->getCountryId());
    }
}
