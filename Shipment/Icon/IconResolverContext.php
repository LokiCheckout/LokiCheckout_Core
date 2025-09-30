<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\Icon;

use Loki\Components\Util\Block\ImageRenderer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

class IconResolverContext
{
    private ?DriverInterface $fileDriver = null;

    public function __construct(
        private readonly ComponentRegistrar $componentRegistrar,
        private readonly ImageRenderer $imageRenderer,
        Filesystem $filesystem,
        private readonly AssetRepository $assetRepository,
        private readonly string $shipmentMethodCode
    ) {
        $this->fileDriver = $filesystem->getDirectoryWrite(DirectoryList::PUB)->getDriver();
    }

    public function getShipmentMethodCode(): string
    {
        return $this->shipmentMethodCode;
    }

    public function getIconPath(string $moduleName, string $path): false|string
    {
        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        $iconPath = $modulePath . DIRECTORY_SEPARATOR . $path;
        if ($this->fileDriver->isFile($iconPath)) {
            return $iconPath;
        }

        return false;
    }

    public function getIconOutput(false|string $iconPath, string $iconType): string
    {
        if (false === $iconPath) {
            return '';
        }

        if (false === $this->fileDriver->isFile($iconPath)) {
            return '';
        }

        if ($iconType === 'svg') {
            return $this->imageRenderer->getIconOutput($iconPath);
        }

        return '';
    }

    public function getViewFileUrl(string $imageId): string
    {
        return $this->assetRepository->getUrl($imageId);
    }
}
