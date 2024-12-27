<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;

class AgreementsContext extends GenericContext
{
    public function getCheckoutAgreementsList(): CheckoutAgreementsListInterface
    {
        return $this->get(CheckoutAgreementsListInterface::class);
    }

    public function getActiveStoreAgreementsFilter(): ActiveStoreAgreementsFilter
    {
        return $this->get(ActiveStoreAgreementsFilter::class);
    }

    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->get(SearchCriteriaBuilder::class);
    }
}
