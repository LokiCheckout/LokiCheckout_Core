<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Yireo\MagewireCheckout\Magewire\AbstractComponent;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;
use Magento\Framework\Phrase;

/**
 * @todo Move this towards a DI dependency instead
 */
abstract class EavFormFieldComponent extends AbstractComponent implements FormFieldInterface
{
    public string $value = '';

    public function __construct(
        private Config $eavConfig,
        CheckoutState $checkoutState
    ) {
        parent::__construct($checkoutState);
    }

    public function boot(): void
    {
        $this->value = (string)$this->checkoutState->getQuote()->getShippingAddress()->getData($this->getAttributeCode());
    }

    public function updated($value, string $name)
    {
        $this->checkoutState->getQuote()->getShippingAddress()->setData($this->getAttributeCode(), $value);
        $this->checkoutState->getQuote()->save();

        $this->emit('quoteSave', $this->checkoutState->getQuote());

        return $value;
    }

    public function getFieldType(): string
    {
        if ($this instanceof FormTextSelectFieldInterface) {
            return 'text_select';
        }

        if ($this instanceof FormSelectFieldInterface) {
            return 'select';
        }

        return 'text';
    }

    public function getInputType(): string
    {
        return 'text';
    }

    public function getAttributeLabel(): Phrase|string
    {
        return $this->getAttribute()->getFrontendLabel();
    }

    public function getValue(): string
    {
        return (string)$this->value;
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }

    /**
     * @return AbstractAttribute
     * @throws LocalizedException
     */
    private function getAttribute(): AbstractAttribute
    {
        return $this->eavConfig->getAttribute('customer_address', $this->getAttributeCode());
    }
}
