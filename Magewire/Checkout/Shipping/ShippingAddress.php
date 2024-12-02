<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Shipping;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\JsNameBehaviour;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Magento\Customer\Model\Address\Mapper as AddressMapper;

class ShippingAddress extends Component
{
    use JsNameBehaviour;

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
        private CheckoutSession $checkoutSession
    ) {
    }

    public function boot()
    {
        if ($this->customerSession->isLoggedIn()) {
            $this->bootForCustomer();
        }

        $this->newAddress = $this->loadNewAddress();
    }

    private function bootForCustomer()
    {
        $quote = $this->checkoutState->getQuote();
        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
        $quote->assignCustomer($customer);

        $this->shippingAddresses = $this->getCustomerAddresses($customer);
        $this->currentShippingAddress = (string)$customer->getDefaultShipping();

        if ($this->currentShippingAddress) {
            $customerShippingAddress = $this->customerAddressRepository->getById((int)$this->currentShippingAddress);
            $quoteShippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
            $quoteShippingAddress->importCustomerAddressData($customerShippingAddress);
            $quoteShippingAddress->setCustomerAddressId($customerShippingAddress->getId());
            $quoteShippingAddress->save();
        }
    }

    public function isEnabled(): bool
    {
        return false === (bool)$this->checkoutState->getQuote()->isVirtual();
    }

    private function loadNewAddress(): bool
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return true;
        }

        if (empty($this->shippingAddresses)) {
            return true;
        }

        return (bool)$this->checkoutSession->getNewAddress();
    }

    public function setNewAddress($newAddress): void
    {
        $newAddress = (bool)$newAddress;
        $this->checkoutSession->setNewAddress($newAddress);
        $this->newAddress = $newAddress;
    }

    public function updatedCurrentShippingAddress(mixed $value): mixed
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return $value;
        }

        if (false === (int)$value > 0) {
            return $value;
        }

        $customerShippingAddress = $this->customerAddressRepository->getById((int)$value);
        $quoteShippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        $quoteShippingAddress->importCustomerAddressData($customerShippingAddress);
        $quoteShippingAddress->setCustomerAddressId($customerShippingAddress->getId());
        $quoteShippingAddress->save();
        $this->emit('afterSaveShippingAddress');

        return $value;
    }

    public function cleanCustomerAddress(): void
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return;
        }

        $quoteShippingAddress = $this->checkoutState->getQuote()->getShippingAddress();
        $quoteShippingAddress->setCustomerAddressId(0);
        $quoteShippingAddress->save();
        $this->emit('afterSaveShippingAddress');
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
