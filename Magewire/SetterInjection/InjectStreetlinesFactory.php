<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;

use Yireo\LokiCheckout\Util\StreetlinesFactory;

trait InjectStreetlinesFactory
{
    protected ?StreetlinesFactory $streetlinesFactory = null;

    public function setStreetlinesFactory(StreetlinesFactory $streetlinesFactory)
    {
        $this->streetlinesFactory = $streetlinesFactory;
    }
}
