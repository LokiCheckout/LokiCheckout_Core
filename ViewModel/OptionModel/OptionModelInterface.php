<?php

namespace Yireo\LokiCheckout\ViewModel\OptionModel;

use Magewirephp\Magewire\Component;

interface OptionModelInterface
{
    public function getOptions(Component $component): array;
}
