<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Validator;

interface ValidatorInterface
{
    public function validate(): true|array;
}
