<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

/**
 * @method AgreementsRepository getRepository()
 */
class AgreementsViewModel extends CheckoutViewModel
{
    public function allowRendering(): bool
    {
        return $this->getCheckoutAgreements() !== [];
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
        $savedAgreements = (array)$this->getContext()->getCheckoutState()->getCheckoutSession()->getAgreementIds();

        $agreements = [];
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $isSaved = in_array($agreement->getId(), $savedAgreements);
            $agreements[$agreement->getId()] = $isSaved;
        }

        return [
            ...parent::getJsData(),
            'agreements' => $agreements
        ];
    }
}
