<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\Util\SameAddressService;

class AddressContext extends GenericContext
{
    public function getSameAddressService(): SameAddressService
    {
        return $this->get(SameAddressService::class);
    }

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

    public function getAddressRenderer(): AddressRenderer
    {
        return $this->get(AddressRenderer::class);
    }
}
