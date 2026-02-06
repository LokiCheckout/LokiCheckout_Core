<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Magento\Customer\Api\AddressRepositoryInterface as CustomerAddressRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use LokiCheckout\Core\Util\AddressRenderer;
use LokiCheckout\Core\Util\CustomerProvider;
use LokiCheckout\Core\Util\SameAddressService;

/**
 * @method CustomerProvider getCustomerProvider()
 * @method SameAddressService getSameAddressService()
 * @method CustomerRepositoryInterface getCustomerRepository()
 * @method CustomerAddressRepository getCustomerAddressRepository()
 * @method SearchCriteriaBuilder getSearchCriteriaBuilder()
 * @method AddressConfig getAddressConfig()
 * @method AddressMapper getAddressMapper()
 * @method AddressRenderer getAddressRenderer()
 * @mixin CheckoutContext
 */
class AddressContext extends AbstractComponentContext
{
}
