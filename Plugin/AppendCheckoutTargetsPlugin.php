<?php declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use Loki\Components\Component\Component;

class AppendCheckoutTargetsPlugin
{
    /**
     * @param Component $subject
     * @param array     $result
     *
     * @return array
     */
    public function afterGetTargets(Component $subject, array $result): array
    {
        // Refresh the private data when updating any checkout component
        if (str_contains($subject->getName(), 'loki-checkout')) {
            $result[] = 'loki-checkout.utils.private-data';
        }

        return $result;
    }
}
