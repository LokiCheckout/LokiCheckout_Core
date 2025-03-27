<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\Api\ServiceFactory;
use Magento\TestFramework\Fixture\DataFixtureInterface;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class CustomerAddressFixture implements RevertibleDataFixtureInterface
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
    ];

    public function __construct(
        private ServiceFactory $serviceFactory,
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        $data = array_merge($this->defaultData, $data);
        $addressSaveService = $this->serviceFactory->create(AddressRepositoryInterface::class, 'save');
        $addressSaveService->execute(['address' => $data]);

        return null;
    }

    public function revert(DataObject $data): void
    {
        if (!$data->getId()) {
            return;
        }

        $data->setAddressId($data->getId());
        $service = $this->serviceFactory->create(AddressRepositoryInterface::class, 'deleteById');
        $service->execute(
            [
                'addressId' => $data->getId()
            ]
        );
    }
}
