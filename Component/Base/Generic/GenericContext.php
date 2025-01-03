<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\Util\CustomerProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentContext;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Yireo\LokiComponents\Messages\GlobalMessageRegistry;

class GenericContext extends ComponentContext
{
    public function getCheckoutState(): CheckoutState
    {
        return $this->get(CheckoutState::class);
    }

    public function getShippingAddressState(): ShippingAddressState
    {
        return $this->get(ShippingAddressState::class);
    }

    public function getBillingAddressState(): BillingAddressState
    {
        return $this->get(BillingAddressState::class);
    }

    public function getCustomerProvider(): CustomerProvider
    {
        return $this->get(CustomerProvider::class);
    }

    public function getCheckoutSession(): CheckoutSession
    {
        return $this->get(CheckoutSession::class);
    }

    public function getStepProvider(): StepProvider
    {
        return $this->get(StepProvider::class);
    }

    public function getStepNavigator(): StepNavigator
    {
        return $this->get(StepNavigator::class);
    }

    public function getModuleConfig(): Config
    {
        return $this->get(Config::class);
    }

    public function getScopeConfig(): ScopeConfigInterface
    {
        return $this->get(ScopeConfigInterface::class);
    }

    public function getAttributeProvider(): AttributeProvider
    {
        return $this->get(AttributeProvider::class);
    }

    public function getCartRepository(): CartRepositoryInterface
    {
        return $this->get(CartRepositoryInterface::class);
    }

    public function getGlobalMessageRegistry(): GlobalMessageRegistry
    {
        return $this->get(GlobalMessageRegistry::class);
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

    public function __call(string $name, array $arguments)
    {
        if (static::class === GenericContext::class) {
            throw new \RuntimeException('Method "'.$name.'" not found in '.__CLASS__);
        }

        $parent = ObjectManager::getInstance()->get(GenericContext::class);
        return call_user_func([$parent, $name], $arguments);
    }

    protected function get(string $className)
    {
        return ObjectManager::getInstance()->get($className);
    }
}
