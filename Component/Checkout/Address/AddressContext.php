<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\Util\SameAddressService;
use Yireo\LokiCheckout\Util\StreetLinesFactory;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class AddressContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly GenericContext $parentContext,
        private readonly SameAddressService $sameAddressService,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
        private readonly AddressRenderer $addressRenderer,
    ) {
    }

    public function getSameAddressService(): SameAddressService
    {
        return $this->sameAddressService;
    }

    public function getCustomerRepository(): CustomerRepositoryInterface
    {
        return $this->customerRepository;
    }

    public function getCustomerAddressRepository(): CustomerAddressRepository
    {
        return $this->customerAddressRepository;
    }

    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
    }

    public function getAddressConfig(): AddressConfig
    {
        return $this->addressConfig;
    }

    public function getAddressMapper(): AddressMapper
    {
        return $this->addressMapper;
    }

    public function getAddressRenderer(): AddressRenderer
    {
        return $this->addressRenderer;
    }
}
