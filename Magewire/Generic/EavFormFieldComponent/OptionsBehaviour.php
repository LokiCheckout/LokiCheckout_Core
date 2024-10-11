<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent;

use RuntimeException;
use Yireo\FriggaCheckout\ViewModel\SelectOptionsInterface;

trait OptionsBehaviour
{
    protected ?SelectOptionsInterface $options = null;

    public function getOptions(): array
    {
        if (false === $this->options instanceof SelectOptionsInterface) {
            throw new RuntimeException('No options set for "'.get_class($this).'"');
        }

        return $this->options->getOptions();
    }

    public function setOptions(SelectOptionsInterface $options): void
    {
        $this->options = $options;
    }
}
