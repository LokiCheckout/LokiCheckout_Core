<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator;

interface ValidatorInterface
{
    public function validate(): true|array;
}
