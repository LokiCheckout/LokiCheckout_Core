<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

class AgreementsViewModel extends FieldViewModel
{
    public function allowRendering(): bool
    {
        return count($this->getCheckoutAgreements()) > 0;
    }

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
        $agreementIds = [];
        foreach ($this->getCheckoutAgreements() as $agreement) {
            $agreementIds[] = $agreement->getId();
        }

        return [
            ...parent::getJsData(),
            'agreements' => []
        ];
    }
}
