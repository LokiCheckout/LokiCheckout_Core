<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class OrderComment extends AbstractComponent
{
    public string $comment = '';

    public function mount(): void
    {
        $this->comment = $this->checkoutState->getQuote()->getData('comment');
    }

    public function updateComment(string $comment): string
    {
        $this->checkoutState->getQuote()->setData('comment', $comment); // @todo
        return $comment;
    }
}
