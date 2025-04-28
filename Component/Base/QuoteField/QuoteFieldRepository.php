<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiFieldComponents\Component\Base\Field\FieldRepository;

/**
 * @method CheckoutContext getContext()
 */
class QuoteFieldRepository extends FieldRepository
{
    public function getValue(): mixed
    {
        return (string)$this->getQuote()->getData($this->getFieldName());
    }

    public function saveValue(mixed $value): void
    {
        $quote = $this->getQuote();
        $quote->setData($this->getFieldName(), $value);
        $this->getContext()->getCheckoutState()->saveQuote($quote);
    }

    public function getFieldName(): string
    {
        $blockName = $this->component->getBlock()->getNameInLayout();
        $blockNameParts = explode('.', $blockName);
        return array_pop($blockNameParts);
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }
}
