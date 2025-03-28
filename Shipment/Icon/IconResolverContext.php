<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment\Icon;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

class IconResolverContext
{
    private ?DriverInterface $fileDriver = null;

    public function __construct(
        private ComponentRegistrar $componentRegistrar,
        Filesystem $filesystem,
        private AssetRepository $assetRepository,
        private string $shipmentMethodCode
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

        if (false === file_exists($iconPath)) {
            return '';
        }

        if ($iconType === 'svg') {
            return file_get_contents($iconPath);
        }

        return '<img src="' . $iconPath . '" />';
    }

    public function getViewFileUrl(string $imageId): string
    {
        return $this->assetRepository->getUrl($imageId);
    }
}
