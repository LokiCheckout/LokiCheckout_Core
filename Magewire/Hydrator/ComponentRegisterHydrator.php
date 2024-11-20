<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Hydrator;

use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\HydratorInterface;
use Magewirephp\Magewire\Model\RequestInterface;
use Magewirephp\Magewire\Model\ResponseInterface;
use Yireo\LokiCheckout\Magewire\ComponentRegistry;

class ComponentRegisterHydrator implements HydratorInterface
{
    public function __construct(
        private ComponentRegistry $componentRegistry
    ) {
    }

    public function hydrate(Component $component, RequestInterface $request): void
    {
        $this->componentRegistry->register($component);
    }

    public function dehydrate(Component $component, ResponseInterface $response): void
    {
    }
}
