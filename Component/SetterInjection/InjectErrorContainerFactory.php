<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Yireo\LokiCheckout\Util\ErrorContainer;
use Yireo\LokiCheckout\Util\ErrorContainerFactory;

trait InjectErrorContainerFactory
{
    protected ?ErrorContainerFactory $errorContainerFactory = null;
    protected ?ErrorContainer $errorContainer = null;

    public function setErrorContainerFactory(ErrorContainerFactory $errorContainerFactory)
    {
        $this->errorContainerFactory = $errorContainerFactory;
        $this->errorContainer = $this->errorContainerFactory->create();
    }

    public function getErrorContainer(): ErrorContainer
    {
        if ($this->errorContainer === null) {
            throw new \RuntimeException('No error container injected for class "'.get_class($this).'"');
        }

        return $this->errorContainer;
    }
}
