<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;

class CustomerFixture extends Customer
{
    public function apply(array $data = []): ?DataObject
    {
        if (isset($data['email'])) {
            $objectManager = ObjectManager::getInstance();
            $customerRepository = $objectManager->get(CustomerRepositoryInterface::class);

            try {
                return $customerRepository->get($data['email']);
            } catch(\Exception $e) {
            }

        }

        return parent::apply($data);
    }

    public function revert(DataObject $data): void
    {
        parent::revert($data);
    }
}
