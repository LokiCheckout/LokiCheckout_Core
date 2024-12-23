<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiComponents\Component\Repository\Mutator as ParentMutator;

class Mutator extends ParentMutator
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ) {
    }

    public function mutate(array $data = []): void
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
        $this->checkoutSession->setAgreementIds($savedAgreements);
    }

    private function getSavedCheckoutAgreementIds(): array
    {
        $agreementIds = (array)$this->checkoutSession->getAgreementIds();
        if (empty($agreementIds)) {
            return [];
        }

        return $agreementIds;
    }
}
