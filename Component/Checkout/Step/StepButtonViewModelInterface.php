<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Yireo\LokiCheckout\Component\GenericViewModelInterface;

interface StepButtonViewModelInterface extends GenericViewModelInterface
{
    public function getStep(): string;
    public function submit();
}
