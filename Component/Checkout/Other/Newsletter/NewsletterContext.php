<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Newsletter;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use Magento\Framework\UrlFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use LokiCheckout\Core\Config\Config as ModuleConfig;
use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\Util\Component\AttributeProvider;
use LokiCheckout\Core\Util\Component\StepProvider;
use LokiCheckout\Core\Util\CustomerProvider;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Loki\Components\Component\ComponentContextInterface;
use Loki\Components\Messages\GlobalMessageRegistry;
use LokiCheckout\Core\Repository\NewsletterRepository;

class NewsletterContext implements ComponentContextInterface
{
    public function __construct(
        private readonly SubscriptionManagerInterface $subscriptionManager,
        private readonly SubscriberFactory $subscriberFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly AddressStateProvider $addressStateProvider,
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
        private readonly ResourceConnection $resourceConnection,
        private readonly NewsletterRepository $newsletterRepository,
    ) {
    }

    public function getSubscriptionManager(): SubscriptionManagerInterface
    {
        return $this->subscriptionManager;
    }

    public function getSubscriberFactory(): SubscriberFactory
    {
        return $this->subscriberFactory;
    }

    public function getAddressStateProvider(): AddressStateProvider
    {
        return $this->addressStateProvider;
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

    public function getStoreManager(): StoreManagerInterface
    {
        return $this->storeManager;
    }

    public function getResourceConnection(): ResourceConnection
    {
        return $this->resourceConnection;
    }

    public function getNewsletterRepository(): NewsletterRepository
    {
        return $this->newsletterRepository;
    }
}
