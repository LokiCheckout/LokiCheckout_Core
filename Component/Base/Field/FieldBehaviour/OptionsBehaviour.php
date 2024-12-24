<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field\FieldBehaviour;

use RuntimeException;
use Yireo\LokiCheckout\ViewModel\OptionModel\OptionModelInterface;

trait OptionsBehaviour
{
    protected ?OptionModelInterface $optionModel = null;

    public function setOptionModel(OptionModelInterface $optionModel): void
    {
        $this->optionModel = $optionModel;
    }

    public function getOptions(): array
    {
        if (false === $this->optionModel instanceof OptionModelInterface) {
            throw new RuntimeException('No options set for "'.get_class($this).'"');
        }

        return $this->optionModel->getOptions($this);
    }
}
