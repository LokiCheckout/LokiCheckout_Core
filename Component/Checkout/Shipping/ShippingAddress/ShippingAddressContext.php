<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class ShippingAddressContext extends GenericContext
{
    public function getCustomerRepository(): CustomerRepositoryInterface
    {
        return $this->get(CustomerRepositoryInterface::class);
    }

    public function getCustomerAddressRepository(): CustomerAddressRepository
    {
        return $this->get(CustomerAddressRepository::class);
    }

    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->get(SearchCriteriaBuilder::class);
    }

    public function getAddressConfig(): AddressConfig
    {
        return $this->get(AddressConfig::class);
    }

    public function getAddressMapper(): AddressMapper
    {
        return $this->get(AddressMapper::class);
    }

    public function isNewAddress(): bool
    {
        if (false === $this->getCustomerSession()->isLoggedIn()) {
            return true;
        }

        return (bool)$this->getCustomerSession()->getIsNewAddress();
    }
}
