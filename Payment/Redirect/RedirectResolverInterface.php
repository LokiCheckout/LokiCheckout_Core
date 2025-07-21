<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Redirect;

use LokiCheckout\Core\Step\FinalStep\RedirectContext;

interface RedirectResolverInterface
{
    public function resolve(RedirectContext $redirectContext): false|string;
}
