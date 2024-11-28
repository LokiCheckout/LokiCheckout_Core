<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magewirephp\Magewire\Component;

class QuoteDetails extends Component
{
    protected $listeners = ['afterSaveQuote' => 'refresh'];
}
