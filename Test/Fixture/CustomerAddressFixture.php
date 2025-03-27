<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class CustomerAddressFixture implements DataFixtureInterface
{
    public function __construct(
        private AddressRepositoryInterface $addressRepository,
        private AddressInterfaceFactory $addressDataFactory
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        /** @var CustomerInterface $customer */
        $customer = $data['customer'];

        $address = $this->addressDataFactory->create();
        $address->setFirstname($customer->getFirstname())
            ->setLastname($customer->getLastname())
            ->setCountryId($data['country_id'] ?? 'NL')
            ->setRegionId($data['region_id'] ?? '')
            ->setRegion($data['region'] ?? null)
            ->setCity($data['city'] ?? 'Baarn')
            ->setPostcode($data['postcode'] ?? '3743KJ')
            ->setStreet($data['street'] ?? ['Amalialaan 126'])
            ->setTelephone($data['telephone'] ?? '0123456789')
            ->setIsDefaultShipping(true)
            ->setIsDefaultBilling(true)
            ->setCustomerId($customer->getId())
        ;

        $this->addressRepository->save($address);

        return null;
    }
}
