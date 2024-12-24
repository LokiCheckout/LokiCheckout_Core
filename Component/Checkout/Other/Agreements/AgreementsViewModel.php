<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Agreements;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

class AgreementsViewModel extends GenericViewModel
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
}
