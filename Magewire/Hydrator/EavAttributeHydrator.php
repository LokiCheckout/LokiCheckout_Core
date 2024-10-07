<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Hydrator;

use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\HydratorInterface;
use Magewirephp\Magewire\Model\RequestInterface;
use Magewirephp\Magewire\Model\ResponseInterface;

class EavAttributeHydrator implements HydratorInterface
{
    public function hydrate(Component $component, RequestInterface $request): void
    {
    }

    public function dehydrate(Component $component, ResponseInterface $response): void
    {
    }
}
