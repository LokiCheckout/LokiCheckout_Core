<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AgreementsContext getContext()
 */
class AgreementsRepository extends ComponentRepository
{
    public function getSavedCheckoutAgreements(): array
    {
        $this->getContext()->getSearchCriteriaBuilder()->addFilter('agreement_id', $this->getSavedCheckoutAgreementIds(), 'in');

        return $this->getContext()->getCheckoutAgreementsList()->getList($this->getContext()->getSearchCriteriaBuilder()->create());
    }

    /**
     * @return AgreementInterface[]
     */
    public function getCheckoutAgreements(): array
    {
        $searchCriteria = $this->getContext()->getActiveStoreAgreementsFilter()->buildSearchCriteria();

        return $this->getContext()->getCheckoutAgreementsList()->getList($searchCriteria);
    }

    protected function getValue(): mixed
    {
        // @todo: Should this just be stored in the session?
        $value = [];
        $agreementIds = (array)$this->getContext()->getCheckoutSession()->getAgreementIds();
        foreach ($agreementIds as $agreementId) {
            if (empty($agreementId)) {
                continue;
            }

            $value[] = $agreementId;
        }

        return $value;
    }

    protected function saveValue(mixed $data): void
    {
        if (empty($data)) {
            return;
        }

        $incomingAgreements = [];
        foreach ((array)$data as $agreementId) {
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
        $this->getContext()->getCheckoutSession()->setAgreementIds($savedAgreements);
    }

    private function getSavedCheckoutAgreementIds(): array
    {
        $agreementIds = (array)$this->getContext()->getCheckoutSession()->getAgreementIds();
        if (empty($agreementIds)) {
            return [];
        }

        return $agreementIds;
    }

}
