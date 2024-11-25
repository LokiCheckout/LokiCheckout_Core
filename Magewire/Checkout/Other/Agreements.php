<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Other;

use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Quote\Api\CartRepositoryInterface;
use Magewirephp\Magewire\Component;
use Magento\Checkout\Model\Session as CheckoutSession;

class Agreements extends Component
{
    public array $agreements = [];

    public function __construct(
        private CheckoutSession $checkoutSession,
        private CheckoutAgreementsListInterface $checkoutAgreementsList,
        private ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    public function boot(): void
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

    public function updatedAgreements(array $agreements): array
    {
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

        return $agreements;
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
