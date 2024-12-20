<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Component\GenericInterface;

interface FieldInterface extends GenericInterface
{
    /**
     * Name of the field itself.
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Name of the field itself.
     *
     * @return string
     */
    public function getFieldLabel(): string;

    /**
     * PHTML template to render this specific field
     *
     * @return string
     */
    public function getFieldTemplate(): string;

    /**
     * In case the field is a text input, this represents the text type (aka `type="email"). By default, this is `text.
     *
     * @return string
     */
    public function getInputType(): string;

    /**
     * Determine if this field is required or not.
     *
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * Determine if this field its value is valid or not.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * @return array
     */
    public function getValidators(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    public function getStep(): string;
}
