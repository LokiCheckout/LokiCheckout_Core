<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Form\Field;

use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\ValidateBehaviour;
use Yireo\LokiCheckout\Component\SetterInjection\InjectErrorContainerFactory;
use Yireo\LokiComponents\Mutator\MutatorInterface;

abstract class Field extends Generic implements MutatorInterface
{
    use InjectErrorContainerFactory;

    use FilterBehaviour;
    use ValidateBehaviour;

    protected string $step;

    public function mutate(array $data = []): void
    {
        if (!isset($data['value'])) {
            return;
        }

        $value = $data['value'];
        $this->errorContainer->clear();
        $value = $this->filterValue($value);

        if (false === $this->errorContainer->hasErrors()) {
            $this->beforeSave($value);
            $this->save($value);
            $this->afterSave($value);
        }
    }

    protected function beforeSave($value): void
    {
    }

    abstract public function save($value): void;

    protected function afterSave($value): void
    {
    }

    public function setStep(string $step)
    {
        $this->step = $step;
    }
}
