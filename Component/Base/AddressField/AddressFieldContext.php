<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\AddressField;

use Loki\Components\Component\AbstractComponentContext;

/**
 * @method \LokiCheckout\Core\Util\Component\AddressStateProvider getAddressStateProvider()
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
class AddressFieldContext extends AbstractComponentContext
{
}
