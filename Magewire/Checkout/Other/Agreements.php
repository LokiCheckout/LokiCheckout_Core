<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Other;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magewirephp\Magewire\Component;
use Magento\Checkout\Model\Session as CheckoutSession;

class Agreements extends Component
{
    public array $agreements = [];

    public function __construct(
        private CheckoutSession $checkoutSession,
        private CheckoutAgreementsListInterface $checkoutAgreementsList,
        private ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
    ) {
    }

    public function boot(): void
    {
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $this->agreements[$agreement->getAgreementId()] = false;
        }

        foreach ($this->getSavedCheckoutAgreements() as $savedAgreement) {
            $this->agreements[$savedAgreement->getAgreementId()] = true;
        }

        $this->agreements;
    }

    public function isEnabled(): bool
    {
        return count($this->getCheckoutAgreements()) > 0;
    }

    public function updatedAgreements(array $agreements): array
    {
        $quote = $this->checkoutSession->getQuote();
        $savedAgreements = (array)$quote->getPayment()->getExtensionAttributes()->getAgreementIds();

        foreach ($agreements as $agreementId => $agreementValue) {
            if (in_array($agreementId, $savedAgreements) && $agreementValue === true) {
                continue;
            }

            if (in_array($agreementId, $savedAgreements) && $agreementValue === false) {
                unset($agreements[$agreementId]);
                continue;
            }

            // @todo: This does not work yet
            $savedAgreements[] = $agreementId;
        }

        $savedAgreements = array_unique($savedAgreements);
        $quote->getPayment()->getExtensionAttributes()->setAgreementIds($savedAgreements);
        $quote->getPayment()->save();
        $quote->save();
        return $agreements;
    }

    public function getSavedCheckoutAgreements(): array
    {
        $quote = $this->checkoutSession->getQuote();
        return (array)$quote->getPayment()->getExtensionAttributes()->getAgreementIds();
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
