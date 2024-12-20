<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiCheckout\Component\Form\Field\Field;

class Agreements extends Field
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private CheckoutAgreementsListInterface $checkoutAgreementsList,
        private ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    public function allowRendering(): bool
    {
        return count($this->getCheckoutAgreements()) > 0;
    }

    public function getValue(): array
    {
        // @todo: Should this just be stored in the session?
        $value = [];
        $agreementIds = (array)$this->checkoutSession->getAgreementIds();
        foreach ($agreementIds as $agreementId) {
            if (empty($agreementId)) {
                continue;
            }

            $value[] = $agreementId;
        }

        return $value;
    }

    public function getSavedCheckoutAgreements(): array
    {
        $this->searchCriteriaBuilder->addFilter('agreement_id', $this->getSavedCheckoutAgreementIds(), 'in');

        return $this->checkoutAgreementsList->getList($this->searchCriteriaBuilder->create());
    }

    /**
     * @return AgreementInterface[]
     */
    public function getCheckoutAgreements(): array
    {
        $searchCriteria = $this->activeStoreAgreementsFilter->buildSearchCriteria();

        return $this->checkoutAgreementsList->getList($searchCriteria);
    }

    public function getFieldLabel(): string
    {
        return 'Agreements';
    }

    public function save($value): void
    {
        // TODO: Implement save() method.
    }
}
