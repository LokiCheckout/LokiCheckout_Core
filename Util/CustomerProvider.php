<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CustomerProvider
{
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
    ) {
    }

    public function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getAddresses(): array
    {
        if (false === $this->isLoggedIn()) {
            return [];
        }

        $customer = $this->getCustomer();
        $this->searchCriteriaBuilder->addFilter('parent_id', $customer->getId());
        $searchResults = $this->customerAddressRepository->getList($this->searchCriteriaBuilder->create());

        return $searchResults->getItems();
    }

    public function getAddressLines(): array
    {
        $addresses = [];
        foreach ($this->getAddresses() as $address) {
            $addresses[$address->getId()] = $this->getAddressInline($address);
        }

        return $addresses;
    }

    public function getDefaultShippingAddressId(): string
    {
        $customer = $this->getCustomer();

        return (string)$customer->getDefaultShipping();
    }

    public function getDefaultBillingAddressId(): string
    {
        $customer = $this->getCustomer();

        return (string)$customer->getDefaultBilling();
    }

    private function getCustomer(): CustomerInterface
    {
        return $this->customerRepository->getById($this->customerSession->getCustomerId());
    }

    private function getAddressInline(AddressInterface $address): string
    {
        $addressData = $this->addressMapper->toFlatArray($address);
        $format = $this->addressConfig->getFormatByCode(AddressConfig::DEFAULT_ADDRESS_FORMAT);
        return $format->getData('renderer')->renderArray($addressData);
    }
}
