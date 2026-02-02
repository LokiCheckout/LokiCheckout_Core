<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Agreements;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * @method SearchCriteriaBuilder getSearchCriteriaBuilder()
 * @method CheckoutAgreementsListInterface getCheckoutAgreementsList()
 * @method ActiveStoreAgreementsFilter getActiveStoreAgreementsFilter()
 * @mixin CheckoutContext
 */
class AgreementsContext extends AbstractComponentContext
{
}
