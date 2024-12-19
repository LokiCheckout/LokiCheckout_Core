<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Button;

use Yireo\LokiCheckout\Component\GenericInterface;

interface ButtonInterface extends GenericInterface
{
    public function getStep(): string;
    public function submit();
}
