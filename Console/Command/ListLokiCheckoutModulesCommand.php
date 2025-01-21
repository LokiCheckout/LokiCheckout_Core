<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Console\Command;

use Magento\Framework\Module\Manager as ModuleManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;

class ListLokiCheckoutModulesCommand extends Command
{
    public function __construct(
        private LokiCheckoutModules $lokiCheckoutModules,
        private ModuleManager $moduleManager,
        ?string $name = null)  {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('yireo:loki-checkout:modules:list');
        $this->setDescription('Show a listing of all registered Loki Checkout modules.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders(['Module', 'Status']);

        foreach ($this->lokiCheckoutModules->getModules() as $module) {
            $table->addRow([
               $module,
                $this->moduleManager->isEnabled($module) ? 'Enabled' : 'Disabled',
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
