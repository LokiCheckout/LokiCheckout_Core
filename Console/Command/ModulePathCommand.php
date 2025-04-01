<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;

class ModulePathCommand extends Command
{
    public function __construct(
        private ComponentRegistrar $componentRegistrar,
        private DirectoryList $directoryList,
        private ReadFactory $directoryReadFactory,
        private LokiCheckoutModules $lokiCheckoutModules,
        private array $relativePaths = [],
        $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('yireo:loki-checkout:module-path');
        $this->setDescription('Return the module path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directoryRead = $this->directoryReadFactory->create('/');

        $moduleNames = $this->lokiCheckoutModules->getActiveModules();

        $paths = [];

        foreach ($moduleNames as $moduleName) {
            $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
            if (empty($modulePath)) {
                continue;
            }

            if (false === str_starts_with($modulePath, '/')) {
                $modulePath = $this->directoryList->getRoot().$modulePath;
            }

            if (false === $directoryRead->isDirectory($modulePath)) {
                continue;
            }

            foreach ($this->relativePaths as $relativePath => $relativePathExpression) {
                $path = $modulePath.'/'.$relativePath;
                if ($directoryRead->isDirectory($path) || $directoryRead->isFile($path)) {
                    $paths[] = $modulePath.'/'.$relativePathExpression;
                }
            }
        }

        foreach ($paths as $path) {
            $output->writeln($path);
        }

        return Command::SUCCESS;
    }
}
