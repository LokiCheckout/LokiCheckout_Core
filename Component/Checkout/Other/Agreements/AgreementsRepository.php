<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AgreementsContext getContext()
 */
class AgreementsRepository extends ComponentRepository
{
    /**
     * @return AgreementInterface[]
     */
    public function getCheckoutAgreements(): array
    {
        $searchCriteria = $this->getContext()->getActiveStoreAgreementsFilter()->buildSearchCriteria();

        return $this->getContext()->getCheckoutAgreementsList()->getList($searchCriteria);
    }

    public function getValue(): mixed
    {
        $savedAgreements = (array)$this->getContext()->getCheckoutState()->getCheckoutSession()->getAgreementIds();

        $value = [];
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $agreementId = $agreement->getAgreementId();
            $value['a' . $agreementId] = in_array($agreementId, $savedAgreements);
        }

        return $value;
    }

    public function saveValue(mixed $value): void
    {
        if (empty($value) || false === is_array($value)) {
            return;
        }

        $savedAgreements = [];
        foreach ($value as $agreementId => $agreed) {
            if ($agreed === false) {
                continue;
            }

            $savedAgreements[] = (int)$agreementId;
        }

        $savedAgreements = array_unique($savedAgreements);

        $this->getContext()->getCheckoutState()->getCheckoutSession()->setAgreementIds($savedAgreements);
    }
}
