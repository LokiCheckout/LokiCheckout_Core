<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

class AgreementsRepository extends ComponentRepository
{
    public function getSavedCheckoutAgreements(): array
    {
        $this->getContext()->searchCriteriaBuilder->addFilter('agreement_id', $this->getSavedCheckoutAgreementIds(), 'in');

        return $this->getContext()->checkoutAgreementsList->getList($this->getContext()->searchCriteriaBuilder->create());
    }

    /**
     * @return AgreementInterface[]
     */
    public function getCheckoutAgreements(): array
    {
        $searchCriteria = $this->getContext()->activeStoreAgreementsFilter->buildSearchCriteria();

        return $this->getContext()->checkoutAgreementsList->getList($searchCriteria);
    }

    protected function getData(): mixed
    {
        // @todo: Should this just be stored in the session?
        $value = [];
        $agreementIds = (array)$this->getContext()->checkoutSession->getAgreementIds();
        foreach ($agreementIds as $agreementId) {
            if (empty($agreementId)) {
                continue;
            }

            $value[] = $agreementId;
        }

        return $value;
    }

    protected function saveData(mixed $data): void
    {
        if (empty($data['value'])) {
            return;
        }

        $incomingAgreements = [];
        foreach ((array)$data['value'] as $agreementId) {
            $incomingAgreements[] = (int)$agreementId;
        }

        $savedAgreements = $this->getSavedCheckoutAgreementIds();

        foreach ($incomingAgreements as $agreementId => $agreementValue) {
            if (in_array($agreementId, $savedAgreements) && $agreementValue === true) {
                continue;
            }

            if (in_array($agreementId, $savedAgreements) && $agreementValue === false) {
                unset($incomingAgreements[$agreementId]);
                continue;
            }

            $savedAgreements[] = $agreementId;
        }

        $savedAgreements = array_unique($savedAgreements);
        $this->getContext()->checkoutSession->setAgreementIds($savedAgreements);
    }

    private function getSavedCheckoutAgreementIds(): array
    {
        $agreementIds = (array)$this->getContext()->checkoutSession->getAgreementIds();
        if (empty($agreementIds)) {
            return [];
        }

        return $agreementIds;
    }

}
