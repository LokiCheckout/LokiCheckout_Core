<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Magento\Quote\Api\Data\CartInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method QuoteFieldContext getContext()
 */
class QuoteFieldRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        return (string)$this->getQuote()->getData($this->getFieldName());
    }

    protected function saveData(mixed $data): void
    {
        $this->getQuote()->setData($this->getFieldName(), $data);
        $this->getQuote()->save(); // @todo
    }

    protected function getFieldName(): string
    {
        $blockName = $this->component->getBlock()->getNameInLayout();
        $blockNameParts = explode('.', $blockName);
        return array_pop($blockNameParts);
    }

    protected function getQuote(): CartInterface
    {
        return $this->getContext()->checkoutSession->getQuote();
    }
}
