<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Generic;

use Loki\Components\Component\AbstractComponentContext;
use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Config\XmlConfig;
use LokiCheckout\Core\Step\StepNavigator;
use LokiCheckout\Core\Util\Component\AttributeProvider;
use LokiCheckout\Core\Util\Component\StepProvider;
use LokiCheckout\Core\Util\CustomerProvider;
use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\ViewModel\State\BillingAddressState;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @method CheckoutState getCheckoutState()
 * @method CheckoutSession getCheckoutSession()
 * @method CustomerSession getCustomerSession()
 * @method UrlFactory getUrlFactory()
 * @method ShippingAddressState getShippingAddressState()
 * @method BillingAddressState getBillingAddressState()
 * @method ManagerInterface getMessageManager()
 * @method CustomerProvider getCustomerProvider()
 * @method StepProvider getStepProvider()
 * @method StepNavigator getStepNavigator()
 * @method Config getModuleConfig()
 * @method XmlConfig getXmlConfig()
 * @method ScopeConfigInterface getScopeConfig()
 * @method AttributeProvider getAttributeProvider()
 * @method ResolverInterface getLocaleResolver()
 * @method StoreManagerInterface getStoreManager()
 * @method Http getRequest()
 */
class CheckoutContext extends AbstractComponentContext
{
    public function getQuote(): Quote
    {
        $quote = $this->getCheckoutState()->getQuote();
        $this->redirectIfNoCartItems();

        return $quote;
    }

    public function redirectIfNoCartItems()
    {
        $quote = $this->getCheckoutState()->getQuote();
        if ($quote->getItemsQty() > 0) {
            return;
        }

        $url = $this->getUrlFactory()->create()->getUrl('checkout/cart');
        throw (new RedirectException((string)__('No items in cart')))->setUrl($url);
    }
}
