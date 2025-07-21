<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Magento\Framework\DataObject;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class ShippingAddressFixture implements RevertibleDataFixtureInterface
{
    private array $defaultData = [
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'country_id' => 'NL',
        'region_id' => 0,
        'region' => null,
        'city' => 'Baarn',
        'postcode' => '3743KJ',
        'street' => ['Amalialaan 126'],
        'telephone' => '0123456789',
        'carrier' => 'flatrate_flatrate',
    ];

    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        $data = array_merge($this->defaultData, $data);

        $cart = $this->cartRepository->get($data['cart_id']);
        $shippingAddress = $cart->getShippingAddress();
        $shippingAddress->addData($data);

        $shippingAddress->setShippingMethod($data['carrier']);
        $shippingAddress->setSameAsBilling(false);
        $cart->getBillingAddress()->setSameAsBilling(false);

        $this->cartRepository->save($cart);
        return null;
    }

    public function revert(DataObject $data): void
    {
        $cart = $this->cartRepository->get($data['cart_id']);
        $shippingAddress = $cart->getShippingAddress();
        $shippingAddress->setData([]);

        $this->cartRepository->save($cart);
    }
}
