<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

interface StepComponentInterface
{
    public function getCode(): string;
    public function getLabel(): string;
    public function isEnabled(): bool; // @todo: Is this still needed?
    public function isAccessable(): bool;
    public function validate():  true|array;
}
