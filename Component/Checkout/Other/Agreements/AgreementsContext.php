<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiComponents\Component\ComponentContextInterface;

/**
 * @method AgreementsContext getContext()
 */
class AgreementsContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutSession $checkoutSession,
        public readonly CheckoutAgreementsListInterface $checkoutAgreementsList,
        public readonly ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        public readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }
}
