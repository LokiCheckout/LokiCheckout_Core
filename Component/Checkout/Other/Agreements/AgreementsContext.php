<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class AgreementsContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly GenericContext $parentContext,
        private readonly CheckoutAgreementsListInterface $checkoutAgreementsList,
        private readonly ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
    ) {
    }

    public function getCheckoutAgreementsList(): CheckoutAgreementsListInterface
    {
        return $this->checkoutAgreementsList;
    }

    public function getActiveStoreAgreementsFilter(): ActiveStoreAgreementsFilter
    {
        return $this->activeStoreAgreementsFilter;
    }

    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
    }
}
