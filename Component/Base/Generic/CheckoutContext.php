<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Generic;

use Loki\Components\Component\AbstractComponentContext;
use Loki\Components\Exception\RedirectException;
use Magento\Quote\Model\Quote;

/**
 * @method \LokiCheckout\Core\ViewModel\CheckoutState getCheckoutState()
 * @method \Magento\Customer\Model\Session getCustomerSession()
 * @method \Magento\Framework\UrlFactory getUrlFactory()
 * @method \LokiCheckout\Core\ViewModel\State\ShippingAddressState getShippingAddressState()
 * @method \LokiCheckout\Core\ViewModel\State\BillingAddressState getBillingAddressState()
 * @method \Magento\Framework\Message\ManagerInterface getMessageManager()
 * @method \LokiCheckout\Core\Util\CustomerProvider getCustomerProvider()
 * @method \LokiCheckout\Core\Util\Component\StepProvider getStepProvider()
 * @method \LokiCheckout\Core\Step\StepNavigator getStepNavigator()
 * @method \LokiCheckout\Core\Config\Config getModuleConfig()
 * @method \Magento\Framework\App\Config\ScopeConfigInterface getScopeConfig()
 * @method \LokiCheckout\Core\Util\Component\AttributeProvider getAttributeProvider()
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

        $this->getMessageManager()->addWarningMessage('No items in cart');
        $url = $this->getUrlFactory()->create()->getUrl('checkout/cart');
        throw new RedirectException($url);
    }
}
