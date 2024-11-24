<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Other;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectCheckoutSession;

// @todo: Move to a separate module
class AccountType extends FieldComponent implements FormTextSelectFieldInterface
{
    use InjectCheckoutSession;
    use OptionsBehaviour;

    public string $value = '';

    public function getFieldLabel(): string
    {
        return (string)__('Account type');
    }

    public function booted(): void
    {
        parent::booted();
        $this->value = (string)$this->checkoutSession->getCustomerAccountType();

    }

    public function save($value): void
    {
        $this->checkoutSession->setCustomerAccountType($value);
    }
}
