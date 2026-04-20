<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Exception;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class CustomerFixture implements RevertibleDataFixtureInterface
{
    public function __construct(
        private CustomerRegistry $customerRegistry,
        private Customer $originalFixture
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        if (isset($data['email'])) {
            try {
                return $this->customerRegistry->retrieveByEmail($data['email']);
            } catch(Exception $e) {
            }

        }

        return $this->originalFixture->apply($data);
    }

    public function revert(DataObject $data): void
    {
        $this->originalFixture->revert($data);
    }
}
