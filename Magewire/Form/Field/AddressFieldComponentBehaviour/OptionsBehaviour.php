<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour;

use RuntimeException;
use Yireo\LokiCheckout\ViewModel\OptionModel\OptionModelInterface;

trait OptionsBehaviour
{
    protected ?OptionModelInterface $optionModel = null;

    public function getOptions(): array
    {
        if (false === $this->optionModel instanceof OptionModelInterface) {
            throw new RuntimeException('No options set for "'.get_class($this).'"');
        }

        return $this->optionModel->getOptions($this);
    }

    public function setOptionModel(OptionModelInterface $optionModel): void
    {
        $this->optionModel = $optionModel;
    }
}
