<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

trait SaveShippingAddressValues
{
    protected function saveShippingAddressValues(array $values = []): void
    {
        /** @var Quote $quote */
        $quote = $this->getCart();
        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertFalse($quote->isVirtual());

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCustomerEmail($values['email'] ?? 'janedoe@example.org');
        $shippingAddress->setFirstname($values['firstname'] ?? 'Jane');
        $shippingAddress->setLastname($values['lastname'] ?? 'Doe');
        $shippingAddress->setStreet($values['street'] ?? 'Testing Street 12');
        $shippingAddress->setPostcode($values['postcode'] ?? '1111AA');
        $shippingAddress->setCity($values['city'] ?? 'Amsterdam');
        $shippingAddress->setRegion($values['region'] ?? 'Noord-Holland');
        $shippingAddress->setCountryId($values['country_id'] ?? 'NL');

        $this->assertNotEmpty($shippingAddress->getWeight());

        $cartRepository = $this->getObjectManager()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);
    }
}
