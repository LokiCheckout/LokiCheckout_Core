<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Config\Config as ModuleConfig;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;
use Yireo\LokiComponents\Messages\GlobalMessageRegistry;

class GenericContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private ComponentContext $parentContext,
        private CheckoutSession $checkoutSession,
        private CheckoutState $checkoutState,
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private CustomerProvider $customerProvider,
        private StepProvider $stepProvider,
        private StepNavigator $stepNavigator,
        private ModuleConfig $moduleConfig,
        private ScopeConfigInterface $scopeConfig,
        private AttributeProvider $attributeProvider,
        private CartRepositoryInterface $cartRepository,
        private GlobalMessageRegistry $globalMessageRegistry,
    ) {
    }

    public function getCheckoutSession(): CheckoutSession
    {
        return $this->checkoutSession;
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

    public function getCartRepository(): CartRepositoryInterface
    {
        return $this->cartRepository;
    }

    public function getGlobalMessageRegistry(): GlobalMessageRegistry
    {
        return $this->globalMessageRegistry;
    }

    public function getQuote(): Quote
    {
        static $quote = null;
        if ($quote instanceof Quote) {
            return $quote;
        }

        $quote = $this->getCheckoutSession()->getQuote();
        return $quote;
    }
}
