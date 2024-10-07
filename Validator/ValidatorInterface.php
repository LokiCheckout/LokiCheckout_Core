<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Validator;

interface ValidatorInterface
{
    public function validate(): true|array;
}
