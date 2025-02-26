<?php

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

use Yireo\LokiCheckout\ValueObject\Adminhtml\CheckResultCollection;

interface SystemCheckInterface
{
    public function getCheckResultCollection(): CheckResultCollection;
}
