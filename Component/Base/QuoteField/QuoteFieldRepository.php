<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\QuoteField;

use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Field\Component\Base\Field\FieldRepository;

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
        if ($value !== null && false === is_scalar($value)) {
            return;
        }

        // Fields that are not rendered (like the email of a logged-in customer) can not be modified either
        if (false === $this->getViewModel()->isAllowRendering()) {
            return;
        }

        $quote = $this->getQuote();
        $quote->setData($this->getFieldName(), $value === null ? null : (string)$value);
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
