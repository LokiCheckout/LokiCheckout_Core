<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomerDataCollector
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ) {
    }

    public function getData(int $customerId): array
    {
        $data = [
            'id' => $customerId,
            'group_id' => 0,
        ];

        if ($customerId > 0) {
            $customer = $this->customerRepository->getById($customerId);
            $data['customer']['group_id'] = $customer->getGroupId();
        }

        return $data;
    }
}
