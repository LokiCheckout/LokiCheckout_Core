<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Console\Command;

use Magento\Framework\Module\Manager as ModuleManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $this->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Format', 'table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $format = (string) $input->getOption('format');

        if ($format === 'text') {
            $this->renderText($output);
        }

        if ($format === 'table') {
            $this->renderTable($output);
        }

        return Command::SUCCESS;
    }

    private function renderTable(OutputInterface $output)
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
    }

    private function renderText(OutputInterface $output)
    {
        foreach ($this->lokiCheckoutModules->getModules() as $module) {
            if (false === $this->moduleManager->isEnabled($module)) {
                continue;
            }

            $output->writeln($module);
        }
    }
}
