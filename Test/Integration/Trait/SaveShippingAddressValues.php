<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;

trait SaveShippingAddressValues
{
    protected function saveShippingAddressValues(array $values = []): void
    {
        $quote = $this->getQuote();
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

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);
    }
}
