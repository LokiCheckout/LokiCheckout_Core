<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Form\Field\QuoteFieldComponent;

class CustomerDob extends QuoteFieldComponent
{
    protected array $validators = ['dob'];

    public function boot(): void
    {
        parent::boot();
        $this->getParent()->setMax(date('Y-m-d', time()));
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
