<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

interface FormFieldInterface
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
     * @todo: Why is this needed?
     *
     * @param mixed $value
     * @return mixed
     */
    public function setValue(mixed $value);

    /**
     * Determine if this field is required or not.
     *
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * Get a JavaScript name (CamelCase)
     *
     * @return string
     */
    public function getAlpineComponentName(): string;

    /**
     * Determine if this field its value is valid or not.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Determine if this field is visible or not.
     *
     * @return bool
     */
    public function isVisible(): bool;
}
