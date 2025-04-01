<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Message\ManagerInterface as CoreMessageManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Yireo\LokiCheckout\Config\Config as ModuleConfig;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContextInterface;
use Yireo\LokiComponents\Exception\RedirectException;
use Yireo\LokiComponents\Messages\GlobalMessageRegistry;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlFactory;
use Yireo\LokiFieldComponents\Util\Component\FieldTemplateLocator;

class CheckoutContext implements ComponentContextInterface
{
    public function __construct(
        private FieldTemplateLocator $fieldTemplateLocator,
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
        private CoreMessageManager $coreMessageManager,
        private readonly CustomerSession $customerSession,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getFieldTemplateLocator(): FieldTemplateLocator
    {
        return $this->fieldTemplateLocator;
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

    public function getQuote(): CartInterface
    {
        return $this->checkoutState->getQuote();
    }

    public function getCart(): CartInterface
    {
        return $this->checkoutState->getQuote();
    }

    public function getGlobalMessageRegistry(): GlobalMessageRegistry
    {
        return $this->globalMessageRegistry;
    }

    public function getCoreMessageManager(): CoreMessageManager
    {
        return $this->coreMessageManager;
    }

    public function getQuote(): CartInterface
    {
        $quote = $this->checkoutState->getQuote();
        $this->redirectIfNoCartItems();

        return $quote;
    }

    public function redirectIfNoCartItems()
    {
        $quote = $this->checkoutState->getQuote();
        if ($quote->getItemsQty() > 0) {
            return;
        }

        $this->getCoreMessageManager()->addWarningMessage('No items in cart');
        $url = $this->getUrlFactory()->create()->getUrl('checkout/cart');
        throw new RedirectException($url);
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
