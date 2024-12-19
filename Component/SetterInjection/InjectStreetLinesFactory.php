<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Yireo\LokiCheckout\Util\StreetLinesFactory;

trait InjectStreetLinesFactory
{
    protected ?StreetLinesFactory $streetLinesFactory = null;

    public function setStreetLinesFactory(StreetLinesFactory $streetLinesFactory)
    {
        $this->streetLinesFactory = $streetLinesFactory;
    }
}
