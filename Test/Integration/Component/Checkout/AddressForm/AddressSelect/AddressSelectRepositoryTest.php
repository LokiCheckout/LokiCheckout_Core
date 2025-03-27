<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\AddressForm\AddressSelect;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressSelect\AddressSelectRepository;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutRepositoryTestCase;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer'),
    AppArea('frontend'),
]
class AddressSelectRepositoryTest extends LokiCheckoutRepositoryTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.select';

    public function testGetValue()
    {
        /** @var CustomerInterface $customer */
        $customer = $this->fixtures->get('customer');

        /** @var Session $customerSession */
        $customerSession = ObjectManager::getInstance()->get(Session::class);
        $customerSession->isLoggedIn();
        $customerSession->setCustomerId($customer->getId());

        $component = $this->getComponent(self::BLOCK_NAME);

        /** @var AddressSelectRepository $repository */
        $repository = $component->getRepository();
        $value = $repository->getValue();
        $this->assertSame('', $value);
    }
}
