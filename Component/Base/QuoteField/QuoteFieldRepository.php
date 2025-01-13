<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
 */
class QuoteFieldRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        return (string)$this->getQuote()->getData($this->getFieldName());
    }

    public function saveValue(mixed $value): void
    {
        $this->getQuote()->setData($this->getFieldName(), $value);
        $this->getContext()->getCartRepository()->save($this->getQuote());
    }

    protected function getFieldName(): string
    {
        $blockName = $this->component->getBlock()->getNameInLayout();
        $blockNameParts = explode('.', $blockName);
        return array_pop($blockNameParts);
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getQuote();
    }
}
