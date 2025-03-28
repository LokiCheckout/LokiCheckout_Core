<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Payment\Redirect;

use Yireo\LokiCheckout\Step\FinalStep\RedirectContext;

interface RedirectResolverInterface
{
    public function resolve(RedirectContext $redirectContext): false|string;
}
