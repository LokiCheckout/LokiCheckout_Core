<?php declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Icon;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Loki\Components\Util\Block\ImageRenderer;
use Magento\Framework\View\Asset\Source as AssetSource;

class IconResolverContext
{
    private ?DriverInterface $fileDriver = null;

    public function __construct(
        Filesystem $filesystem,
        private readonly ComponentRegistrar $componentRegistrar,
        private readonly AssetRepository $assetRepository,
        private readonly AssetSource $assetSource,
        private readonly ImageRenderer $imageRenderer,
        private readonly string $paymentMethodCode,
        private readonly array $attributes = [],
    ) {
        $this->fileDriver = $filesystem->getDirectoryWrite(DirectoryList::PUB)->getDriver();
    }

    public function getPaymentMethodCode(): string
    {
        return $this->paymentMethodCode;
    }

    public function getIconAsset(string $assetId, array $suffixes = ['png', 'svg']): false|string
    {
        $assetSource = false;
        $asset = false;
        foreach ($suffixes as $suffix) {
            $asset = $this->assetRepository->createAsset($assetId.'.'.$suffix);
            $assetSource = $this->assetSource->findSource($asset);
            if ($assetSource) {
                break;
            }
        }

        if (false === $asset || false === $assetSource) {
            return false;
        }

        if (str_ends_with($assetSource, '.svg')) {
            return $this->getIconOutput($assetSource, 'svg');
        }

        return $this->imageRenderer->get($asset->getUrl(), $this->attributes);
    }

    public function getIconPath(string $moduleName, string $path): false|string
    {
        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        $iconPath = $modulePath.DIRECTORY_SEPARATOR.$path;
        if ($this->fileDriver->isFile($iconPath)) {
            return $iconPath;
        }

        return false;
    }

    public function getIconOutput(false|string $iconPath, string $iconType = '', array $htmlAttributes = []): string
    {
        if (false === $iconPath) {
            return '';
        }

        $attributes = array_merge($this->getAttributes(), $htmlAttributes);
        if ($this->fileDriver->isFile($iconPath) && $iconType === 'svg') {
            return $this->imageRenderer->getIconOutput($iconPath, $attributes);
        }

        if ($this->fileDriver->isFile($iconPath)) {
            return $this->imageRenderer->getByFile($iconPath);
        }

        return $this->imageRenderer->getByUrl($iconPath, $attributes);
    }

    public function getViewFileUrl(string $imageId): string
    {
        return $this->assetRepository->getUrl($imageId);
    }

    public function isValidViewFileUrl(string $imageId): bool
    {
        [$moduleName, $imagePath] = explode('::', $imageId);
        $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        $webPaths = [
            'view/frontend/web',
            'view/base/web',
        ];

        foreach ($webPaths as $webPath) {
            $path = $modulePath.'/'.$webPath.'/'.$imagePath;
            if ($this->fileDriver->isFile($path)) {
                return true;
            }
        }

        return false;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
