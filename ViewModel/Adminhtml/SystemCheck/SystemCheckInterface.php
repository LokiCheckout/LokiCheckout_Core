<?php

declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck;

use LokiCheckout\Core\ValueObject\Adminhtml\CheckResultCollection;

interface SystemCheckInterface
{
    public function getCheckResultCollection(): CheckResultCollection;
}
