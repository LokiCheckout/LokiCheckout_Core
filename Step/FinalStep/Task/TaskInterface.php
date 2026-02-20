<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Task;

use Magento\Quote\Model\Quote;

interface TaskInterface
{
    public function beforePlaceOrder(Quote $quote): void;

    public function afterPlaceOrder(int $orderId): void;
}
