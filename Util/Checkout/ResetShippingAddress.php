<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\CheckoutState;

class ResetShippingAddress
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private SameAddressService $sameAddressService,
    ) {
    }

    public function reset()
    {
        $fields = $this->getFields();
        $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        $billingAddress = $this->checkoutState->getQuote()->getBillingAddress();

        foreach ($fields as $field) {
            $shippingAddress->setData($field, $billingAddress->getData($field));
        }

        $sameAsBillingDefault = $this->sameAddressService->isEnabled() ? 1 : 0;
        $shippingAddress->setSameAsBilling($sameAsBillingDefault);
        $shippingAddress->setSaveInAddressBook(false);
    }

    public function getFields(): array
    {
        return [
            'firstname',
            'middlename',
            'lastname',
            'company',
            'street',
            'city',
            'region',
            'region_id',
            'country_id',
            'postcode',
            'telephone',
            'fax',
            'vat_id',
            'prefix',
            'suffix',
        ];
    }
}
