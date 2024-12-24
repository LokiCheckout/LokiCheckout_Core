<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

interface StepViewModelInterface extends ComponentViewModelInterface
{
    public function getCode(): string;
    public function getLabel(): string;
    public function isEnabled(): bool; // @todo: Is this still needed?
    public function isAccessable(): bool;
    public function validate():  true|array;
}
