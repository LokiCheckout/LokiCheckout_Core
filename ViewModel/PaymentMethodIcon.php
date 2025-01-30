<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * @api
 */
class PaymentMethodIcon implements ArgumentInterface
{
    private ?DriverInterface $fileDriver = null;


    public function __construct(
        private AssetRepository $assetRepository,
        private ComponentRegistrar $componentRegistrar,
        private ModuleManager $moduleManager,
        Filesystem $filesystem,
    ) {
        $this->fileDriver = $filesystem->getDirectoryWrite(DirectoryList::PUB)->getDriver();
    }

    public function getIcon(string $paymentMethodCode): string
    {
        return '';
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

    public function getIconOutput(string $iconPath, string $iconType): string
    {
        if ($iconType === 'svg') {
            return file_get_contents($iconPath);
        }

        return '<img src="' . $iconPath . '" />';
    }

    public function isModuleEnabled(string $moduleName): bool
    {
        return $this->moduleManager->isEnabled($moduleName);
    }

    public function getAssetRepository(): AssetRepository
    {
        return $this->assetRepository;
    }
}
