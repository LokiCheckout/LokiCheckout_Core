<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiComponents\Component\MutableComponentInterface;

class Agreements implements MutableComponentInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private CheckoutAgreementsListInterface $checkoutAgreementsList,
        private ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    public function initialize(): void
    {
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $this->agreements[$agreement->getAgreementId()] = false;
        }

        foreach ($this->getSavedCheckoutAgreementIds() as $savedAgreementId) {
            $this->agreements[$savedAgreementId] = true;
        }
    }

    public function isEnabled(): bool
    {
        return count($this->getCheckoutAgreements()) > 0;
    }

    public function mutate(array $data = []): void
    {
        if (empty($data['agreements'])) {
            return;
        }

        $agreements = $data['agreements'];
        $savedAgreements = (array)$this->getSavedCheckoutAgreementIds();

        foreach ($agreements as $agreementId => $agreementValue) {
            if (in_array($agreementId, $savedAgreements) && $agreementValue === true) {
                continue;
            }

            if (in_array($agreementId, $savedAgreements) && $agreementValue === false) {
                unset($agreements[$agreementId]);
                continue;
            }

            $savedAgreements[] = $agreementId;
        }

        $savedAgreements = array_unique($savedAgreements);
        $this->checkoutSession->setAgreementIds($savedAgreements);
    }

    public function getSavedCheckoutAgreementIds(): array
    {
        $agreementIds = (array)$this->checkoutSession->getAgreementIds();
        if (empty($agreementIds)) {
            return [];
        }

        return $agreementIds;
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
}
