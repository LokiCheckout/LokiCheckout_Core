<?php

namespace Yireo\FriggaCheckout\ViewModel\OptionModel;

use Magewirephp\Magewire\Component;

interface OptionModelInterface
{
    public function getOptions(Component $component): array;
}
