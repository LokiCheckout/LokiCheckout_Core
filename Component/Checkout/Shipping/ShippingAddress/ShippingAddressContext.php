<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingAddressContext implements ComponentContextInterface
{
    public function __construct(
        public CheckoutState $checkoutState,
        public CustomerSession $customerSession,
        public CustomerRepositoryInterface $customerRepository,
        public CustomerAddressRepository $customerAddressRepository,
        public SearchCriteriaBuilder $searchCriteriaBuilder,
        public AddressConfig $addressConfig,
        public AddressMapper $addressMapper,
        public CheckoutSession $checkoutSession,
        public CustomerProvider $customerProvider,
    ) {
    }

    public function isNewAddress(): bool
    {
        if (false === $this->customerSession->isLoggedIn()) {
            return true;
        }

        return (bool)$this->checkoutSession->getIsNewAddress();
    }
}
