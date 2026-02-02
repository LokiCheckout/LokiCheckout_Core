<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address;

use Loki\Components\Component\AbstractComponentContext;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use Magento\Framework\UrlFactory;
use LokiCheckout\Core\Config\Config as ModuleConfig;
use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\Util\Component\AttributeProvider;
use LokiCheckout\Core\Util\Component\StepProvider;
use LokiCheckout\Core\Util\CustomerProvider;
use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Loki\Components\Messages\GlobalMessageRegistry;

class AddressContext extends AbstractComponentContext
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly BillingAddressState $billingAddressState,
        private readonly CustomerProvider $customerProvider,
        private readonly StepProvider $stepProvider,
        private readonly StepNavigator $stepNavigator,
        private readonly ModuleConfig $moduleConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly AttributeProvider $attributeProvider,
        private readonly GlobalMessageRegistry $globalMessageRegistry,
        private readonly CoreMessageManager $coreMessageManager,
        private readonly UrlFactory $urlFactory,
        private readonly SameAddressService $sameAddressService,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly AddressConfig $addressConfig,
        private readonly AddressMapper $addressMapper,
        private readonly AddressRenderer $addressRenderer,
        protected array $dependencies = []
    ) {
        parent::__construct($dependencies);
    }

    public function getCheckoutState(): CheckoutState
    {
        return $this->checkoutState;
    }

    public function getShippingAddressState(): ShippingAddressState
    {
        return $this->shippingAddressState;
    }

    public function getBillingAddressState(): BillingAddressState
    {
        return $this->billingAddressState;
    }

    public function getCustomerProvider(): CustomerProvider
    {
        return $this->customerProvider;
    }

    public function getStepProvider(): StepProvider
    {
        return $this->stepProvider;
    }

    public function getStepNavigator(): StepNavigator
    {
        return $this->stepNavigator;
    }

    public function getModuleConfig(): ModuleConfig
    {
        return $this->moduleConfig;
    }

    public function getScopeConfig(): ScopeConfigInterface
    {
        return $this->scopeConfig;
    }

    public function getAttributeProvider(): AttributeProvider
    {
        return $this->attributeProvider;
    }

    public function getGlobalMessageRegistry(): GlobalMessageRegistry
    {
        return $this->globalMessageRegistry;
    }

    public function getCoreMessageManager(): CoreMessageManager
    {
        return $this->coreMessageManager;
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->getCheckoutState()->getCustomerSession();
    }

    public function getUrlFactory(): UrlFactory
    {
        return $this->urlFactory;
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
