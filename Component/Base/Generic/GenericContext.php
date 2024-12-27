<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Util\Component\AttributeProvider;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface as MessageManager;

class GenericContext implements ComponentContextInterface
{
    public function getCheckoutState(): CheckoutState
    {
        return $this->get(CheckoutState::class);
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->get(CustomerSession::class);
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

    public function getMessageManager(): MessageManager
    {
        return $this->get(MessageManager::class);
    }

    public function __call(string $name, array $arguments)
    {
        $parent = ObjectManager::getInstance()->get(GenericContext::class);
        return call_user_func([$parent, $name], $arguments);
    }

    protected function get(string $className)
    {
        return ObjectManager::getInstance()->get($className);
    }
}
