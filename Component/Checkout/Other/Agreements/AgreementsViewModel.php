<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

/**
 * @method AgreementsRepository getRepository()
 */
class AgreementsViewModel extends CheckoutViewModel
{
    public function allowRendering(): bool
    {
        return $this->getCheckoutAgreements() !== [];
    }

    public function getStep(): string
    {
        return 'any';
    }

    /**
     * @return AgreementInterface[]
     */
    public function getCheckoutAgreements(): array
    {
        return $this->getRepository()->getCheckoutAgreements();
    }

    public function getFieldLabel(): string
    {
        return 'Agreements';
    }

    public function getJsComponentName(): string
    {
        return 'LokiCheckoutAgreementsComponent';
    }

    public function getJsData(): array
    {
        $savedAgreements = $this->getSavedAgreements();

        $agreements = [];
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $isSaved = in_array($agreement->getId(), $savedAgreements);
            $agreements['LokiCheckoutAgreementComponent'.$agreement->getId()] = $isSaved;
        }

        return [
            ...parent::getJsData(),
            'agreements' => $agreements,
            'errorText' => __($this->getBlock()->getErrorText())
        ];
    }

    public function isValid(): bool
    {
        $savedAgreements = $this->getSavedAgreements();
        $availableAgreements = $this->getCheckoutAgreementIds();
        return count(array_intersect($savedAgreements, $availableAgreements)) === count($availableAgreements);
    }

    private function getCheckoutAgreementIds(): array
    {
        $checkoutAgreementIds = [];
        $checkoutAgreements = $this->getCheckoutAgreements();
        foreach ($checkoutAgreements as $checkoutAgreement) {
            $checkoutAgreementIds[] = $checkoutAgreement->getId();
        }

        return $checkoutAgreementIds;
    }

    private function getSavedAgreements(): array
    {
        return (array)$this->getContext()->getCheckoutState()->getCheckoutSession()->getAgreementIds();
    }
}
