<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\ViewModel;

use Magento\Customer\Model\Vat as VatModel;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\MagewireCheckout\Util\Context;

class VatId implements ArgumentInterface
{
    public function __construct(
        private VatModel $vatModel,
        private Context $context
    ) {
    }

    public function isEu(): bool
    {
        // @todo: Billing or shipping?
        // @todo: Does the shipping address need VAT ID after all?
        $countryId = $this->getCountryIdFromQuote();
        return $this->vatModel->isCountryInEU($countryId);
    }

    public function getVatCountryId(): string
    {
        $countryId = $this->getCountryIdFromQuote();
        // @todo: Greek exception
        return $countryId;
    }

    private function getCountryIdFromQuote(): string
    {
        return (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getCountryId();
    }
}
