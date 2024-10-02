<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

abstract class AbstractComponent extends Component
{
    public function __construct(
        protected CheckoutState $checkoutState
    ) {
    }

    public function getCheckoutState(): CheckoutState
    {
        return $this->checkoutState;
    }
}
