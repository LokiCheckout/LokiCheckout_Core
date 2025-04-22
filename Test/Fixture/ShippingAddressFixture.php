<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Mollie\Payment\Plugin\Quote\Api\PaymentMethodManagementPlugin;
use Yireo\LokiCheckoutMollie\Test\Integration\Stub\PaymentMethodManagementPluginStub;

class ShippingAddressFixture implements DataFixtureInterface
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
        private CartRepositoryInterface $cartRepository,
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

        $objectManager = Bootstrap::getObjectManager();
        $instance = $objectManager->create(PaymentMethodManagementPluginStub::class);

        $objectManager->configure([
            'preferences' => [
                PaymentMethodManagementPlugin::class => PaymentMethodManagementPluginStub::class
            ]
        ]);

        $objectManager->addSharedInstance($instance, PaymentMethodManagementPlugin::class, true);

        return null;
    }
}
