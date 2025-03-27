<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class CustomerLoggedInFixture implements DataFixtureInterface
{
    public function apply(array $data = []): ?DataObject
    {
        /** @var CustomerInterface $customer */
        $customer = $data['customer'];

        /** @var Session $customerSession */
        $customerSession = ObjectManager::getInstance()->get(Session::class);
        $customerSession->isLoggedIn();
        $customerSession->setCustomerId($customer->getId());

        return null;
    }
}
