<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step;

use Loki\Components\Component\ComponentContextInterface;
use LokiCheckout\Core\Validator\ValidatorInterface;

interface StepContextInterface extends ComponentContextInterface
{
    public function getStepValidator(): ValidatorInterface;
}
