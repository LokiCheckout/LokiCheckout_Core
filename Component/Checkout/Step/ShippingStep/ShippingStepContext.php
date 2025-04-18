<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\ShippingStep;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use Magento\Framework\UrlFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Yireo\LokiCheckout\Config\Config as ModuleConfig;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;
use Yireo\LokiComponents\Messages\GlobalMessageRegistry;

class ShippingStepContext implements ComponentContextInterface
{
    public function __construct(
        private readonly ValidatorInterface $stepValidator,
        private CheckoutState $checkoutState,
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private CustomerProvider $customerProvider,
        private StepProvider $stepProvider,
        private StepNavigator $stepNavigator,
        private ModuleConfig $moduleConfig,
        private ScopeConfigInterface $scopeConfig,
        private AttributeProvider $attributeProvider,
        private GlobalMessageRegistry $globalMessageRegistry,
        private CoreMessageManager $coreMessageManager,
        private readonly CustomerSession $customerSession,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getStepValidator(): ValidatorInterface
    {
        return $this->stepValidator;
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
        return $this->customerSession;
    }

    public function getUrlFactory(): UrlFactory
    {
        return $this->urlFactory;
    }
}
