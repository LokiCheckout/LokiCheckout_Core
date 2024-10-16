<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator;

interface ValidatorInterface
{
    public function validate(): true|array;
}
