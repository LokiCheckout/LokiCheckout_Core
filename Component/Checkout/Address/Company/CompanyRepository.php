<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Company;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class CompanyRepository extends AddressFieldRepository
{
    public function save(mixed $data): void
    {
        $this->getLocalMessageManager()->addError('Test error');
        $this->getLocalMessageManager()->addSuccess('Test success');
        $this->getLocalMessageManager()->addWarning('Test warning');
        $this->getLocalMessageManager()->addNotice('Test notice');
        parent::save($data);
    }
}
