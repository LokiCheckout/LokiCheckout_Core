<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiComponents\Component\Mutator\Mutator;

abstract class FieldMutator extends Mutator
{
    protected string $step;

    public function mutate(mixed $data): void
    {
        if (!is_array($data) || !isset($data['value'])) {
            return;
        }

        $value = $data['value'];
        parent::mutate($value);

        if (false === $this->getComponent()->getViewModel()->validate()) {
            return;
        }

        $this->beforeSave($value);
        $this->save($value);
        $this->afterSave($value);
    }

    protected function beforeSave($value): void
    {
    }

    abstract public function save(mixed $data): void;

    protected function afterSave($value): void
    {
    }

    public function setStep(string $step)
    {
        $this->step = $step;
    }

}
