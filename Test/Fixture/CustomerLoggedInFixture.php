<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class CustomerLoggedInFixture implements DataFixtureInterface, RevertibleDataFixtureInterface
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

    public function revert(DataObject $data): void
    {
        try {
            $customerSession = ObjectManager::getInstance()->get(Session::class);
            $customerSession->logout();
        } catch(\Throwable $e) {
        }
    }
}
