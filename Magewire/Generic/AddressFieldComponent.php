<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magewirephp\Magewire\Component;
use RuntimeException;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\AddressStateBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\AttributeBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\FieldTemplateLocatorBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent\ValidateBehaviour;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

abstract class AddressFieldComponent extends Component implements FormFieldInterface, AddressFieldInterface
{
    use AttributeBehaviour;
    use FilterBehaviour;
    use ValidateBehaviour;
    use FieldTemplateLocatorBehaviour;
    use AddressStateBehaviour;


    public string $value = '';

    public function booted(): void
    {
        $this->listeners[static::class.'::afterSetValue'] = 'refresh';
    }


    public function getFieldName(): string
    {
        // @doc: You can override a field name in your own Component class
        // @doc: Field name is based upon non-namespaced classname

        $baseClassname = basename(str_replace('\\', '/', static::class));

        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $baseClassname), '_'));
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getParent();
        if ($parentBlock && $parentBlock->getFieldLabel()) {
            return (string)$parentBlock->getFieldLabel(); // @doc: You can override a field label via XML layout
        }

        return $this->getAttributeLabel();
    }

    public function getInputType(): string
    {
        return 'text'; // @doc: Example for overriding this to "email"
    }

    public function getModelName(): string
    {
        return 'value';
    }

    public function getValue(): string
    {
        return (string)$this->value;
    }

    public function setValue(mixed $value)
    {
        $this->value = $this->filter((string)$value);
        $this->validate();

        if (false === $this->hasErrors()) {
            $this->save($value);
            $this->emit(static::class.'::afterSetValue');
        }
    }

    public function updated($value, string $name)
    {
        $this->setValue($value);
        return $value;
    }

    abstract public function save($value): void;

    abstract public function getNameInLayout(): string;

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }

    public function hasClientSideValidation(): bool
    {
        return (bool)$this->getParent()->getClientSideValidation();
    }

    public function isVisible(): bool
    {
        return true; // @doc: Example for overriding this
    }

    public function getJsName()
    {
        $name = ucwords($this->getNameInLayout(), '_.');

        return preg_replace('#[_.]+#', '', $name);
    }

    protected function setErrorsFromValidation(true|array $errors): bool
    {
        if (true === $errors) {
            return true;
        }

        foreach ($errors as $error) {
            $this->error('value', $error);
        }

        return false;
    }
}
