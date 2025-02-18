<?php

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResult;

interface SystemCheckInterface
{
    public function getResult(): CheckResult;
}
