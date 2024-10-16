<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class OrderComment extends Component
{
    public string $comment = '';

    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function boot(): void
    {
        $this->comment = $this->checkoutState->getQuote()->getData('comment');
    }

    public function updateComment(string $comment): string
    {
        $this->checkoutState->getQuote()->setData('comment', $comment); // @todo
        return $comment;
    }
}
