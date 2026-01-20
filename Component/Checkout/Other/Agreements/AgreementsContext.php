<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use Magento\Framework\UrlFactory;
use LokiCheckout\Core\Config\Config as ModuleConfig;
use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Util\Component\AttributeProvider;
use LokiCheckout\Core\Util\Component\StepProvider;
use LokiCheckout\Core\Util\CustomerProvider;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Loki\Components\Component\ComponentContextInterface;
use Loki\Components\Messages\GlobalMessageRegistry;

class AgreementsContext implements ComponentContextInterface
{
    public function __construct(
        private readonly CheckoutAgreementsListInterface $checkoutAgreementsList,
        private readonly ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
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
    ) {
    }

    public function getCheckoutAgreementsList(): CheckoutAgreementsListInterface
    {
        return $this->checkoutAgreementsList;
    }

    public function getActiveStoreAgreementsFilter(): ActiveStoreAgreementsFilter
    {
        return $this->activeStoreAgreementsFilter;
    }

    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
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

}
