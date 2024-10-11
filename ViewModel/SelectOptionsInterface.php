<?php

namespace Yireo\FriggaCheckout\ViewModel;

use Magewirephp\Magewire\Component;

interface SelectOptionsInterface
{
    public function getOptions(Component $component): array;
}
