<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator;

interface ValidatorInterface
{
    public function validate(): bool|array;
}
