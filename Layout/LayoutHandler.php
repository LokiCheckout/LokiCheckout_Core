<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Layout;


use Loki\Components\Layout\LayoutHandlerInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Util\CurrentTheme;

class LayoutHandler implements LayoutHandlerInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly CurrentTheme $currentTheme,
    ) {
    }

    public function get(array $handles): array
    {
        if (false === in_array('checkout_index_index', $handles)) {
            return $handles;
        }

        $handles = array_merge($handles, $this->getGenericHandles());
        $handles = array_merge($handles, $this->getThemeHandles($handles));
        return array_merge($handles, $this->getAddressGridHandles());
    }

    private function getGenericHandles(): array
    {
        $theme = $this->config->getTheme();
        $handles = [
            'loki_checkout',
            'loki_checkout_theme_'.$theme
        ];

        return $handles;
    }

    private function getThemeHandles(array $handles): array
    {
        if ($this->currentTheme->isHyva()) {
            return $this->getHandlesByTheme($handles,'hyva');
        }

        if ($this->currentTheme->isLuma()) {
            return $this->getHandlesByTheme($handles,'luma');
        }

        if ($this->currentTheme->isBreeze()) {
            return $this->getHandlesByTheme($handles,'breeze');
        }

        return [];
    }

    private function getHandlesByTheme(array $handles, string $theme): array
    {
        $handles = [];
        foreach ($handles as $handle) {
            if (str_starts_with($handle, $theme.'_')) {
                continue;
            }

            $handles[] = $theme.'_'.$handle;
        }

        return $handles;
    }

    private function getAddressGridHandles(): array
    {
        $gridLayout = $this->config->getGridLayoutName();
        $handles = ['loki_checkout_address_'.$gridLayout];

        return $handles;
    }
}
