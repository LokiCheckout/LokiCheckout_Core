<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Quote\Model\Quote\Address;

class UpdatePickupAddress
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function update(Address $address): void
    {
        $fields = $this->getFields();
        $shippingAddress = $this->checkoutState->getQuote()->getShippingAddress();

        foreach ($fields as $field) {
            $shippingAddress->setData($field, $address->getData($field));
        }

        $shippingAddress->setSameAsBilling(0);
        $shippingAddress->setSaveInAddressBook(false);
    }

    public function getFields(): array
    {
        return [
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
