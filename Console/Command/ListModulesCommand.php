<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Console\Command;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\Manager as ModuleManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\LokiCheckout\Config\LokiCheckoutModules;

class ListModulesCommand extends Command
{
    public function __construct(
        private readonly LokiCheckoutModules $lokiCheckoutModules,
        private readonly ComponentRegistrar $componentRegistrar,
        private readonly ModuleManager $moduleManager,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('yireo:loki-checkout:modules:list');
        $this->setDescription('Show a listing of all registered Loki Checkout modules.');
        $this->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Format', 'table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $format = (string)$input->getOption('format');

        if ($format === 'json') {
            $this->renderJson($output);
        }

        if ($format === 'table') {
            $this->renderTable($output);
        }

        return Command::SUCCESS;
    }

    private function renderTable(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Module', 'Status', 'Path']);

        foreach ($this->lokiCheckoutModules->getModules() as $module) {
            $table->addRow([
                $module,
                $this->moduleManager->isEnabled($module) ? 'Enabled' : 'Disabled',
                $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $module),
            ]);
        }

        $table->render();
    }

    private function renderJson(OutputInterface $output)
    {
        $data = [];
        foreach ($this->lokiCheckoutModules->getModules() as $module) {
            $data[] = [
                'name' => $module,
                'enabled' => (int)$this->moduleManager->isEnabled($module),
                'path' => $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $module),
            ];
        }

        $output->writeln(json_encode($data, JSON_PRETTY_PRINT));
    }
}
