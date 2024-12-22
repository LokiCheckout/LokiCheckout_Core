<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Dob;

use Yireo\LokiCheckout\Component\Form\Field\QuoteFieldViewModel;

class ViewModel extends QuoteFieldViewModel
{
    protected array $validators = ['dob'];

    public function initialize(): void
    {
        parent::initialize();
        $this->getBlock()->setMax(date('Y-m-d', time()));
    }

    public function getJsValue(): string
    {
        if (!empty($this->value)) {
            return date('Y-m-d', strtotime($this->value));
        }

        return '';
    }

    public function getFieldLabel(): string
    {
        return (string)__('Date of Birth');
    }

    public function getInputType(): string
    {
        return 'date';
    }
}
