<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class CustomerLoggedInFixture implements DataFixtureInterface
{
    public function apply(array $data = []): ?DataObject
    {
        $customerId = (int) $data['customer_id'];

        /** @var Session $customerSession */
        $customerSession = ObjectManager::getInstance()->get(Session::class);
        $customerSession->isLoggedIn();
        $customerSession->setCustomerId($customerId);

        return null;
    }
}
