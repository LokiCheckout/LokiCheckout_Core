<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep;

use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magewirephp\Magewire\Component;
use Override;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Magento\Customer\Model\Address\Mapper as AddressMapper;

class ShippingAddress extends Component
{
    public array $shippingAddresses = [];
    public string $currentShippingAddress = '';
    public bool $newAddress = false;

    public function __construct(
        private CheckoutState $checkoutState,
        private CustomerSession $customerSession,
        private CustomerRepositoryInterface $customerRepository,
        private CustomerAddressRepository $customerAddressRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private AddressConfig $addressConfig,
        private AddressMapper $addressMapper,
    ) {
    }

    public function boot()
    {
        if (false === $this->customerSession->isLoggedIn()) {
            $this->newAddress = true;
            return;
        }

        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
        $this->checkoutState->getQuote()->assignCustomer($customer);

        $this->currentShippingAddress = (string)$customer->getDefaultShipping();
        $this->shippingAddresses = $this->getCustomerAddresses($customer);

        if (empty($this->shippingAddresses)) {
            $this->newAddress = true;
        }
    }


    public function updated(mixed $value, string $name): mixed
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return $value;
        }

        if ($name !== 'currentShippingAddress') {
            return $value;
        }

        if (false === (int)$value > 0) {
            return $value;
        }

        $shippingAddress = $this->customerAddressRepository->getById((int)$value);
        $this->checkoutState->getQuote()->getShippingAddress()->importCustomerAddressData($shippingAddress);
        $this->checkoutState->getQuote()->getShippingAddress()->save();
        $this->emit('shippingStepSave');

        return $value;
    }

    public function addAddress()
    {
        $this->newAddress = true;
    }

    private function getCustomerAddresses(CustomerInterface $customer): array
    {
        $this->searchCriteriaBuilder->addFilter('parent_id', $customer->getId());
        $searchResults = $this->customerAddressRepository->getList($this->searchCriteriaBuilder->create());

        $addresses = [];
        foreach ($searchResults->getItems() as $address) {
            $addresses[$address->getId()] = $this->getAddressInline($address);
        }

        return $addresses;
    }

    private function getAddressInline(AddressInterface $address): string
    {
        $addressData = $this->addressMapper->toFlatArray($address);
        return $this->addressConfig
            ->getFormatByCode(AddressConfig::DEFAULT_ADDRESS_FORMAT)
            ->getRenderer()
            ->renderArray($addressData);
    }
}
