<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Task\TaskInterface;
use Psr\Log\LoggerInterface;

class TaskListing
{
    public const REQUIRED_TASKS = ['agreements', 'recaptcha', 'saveInAddressBook', 'newsletter'];

    private bool $requiredTasksChecked = false;

    public function __construct(
        private array $tasks = [],
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * @return TaskInterface[]
     */
    public function getTasks(): array
    {
        $this->checkRequiredTasks();

        return $this->tasks;
    }

    private function checkRequiredTasks(): void
    {
        if ($this->requiredTasksChecked || null === $this->logger) {
            return;
        }

        $this->requiredTasksChecked = true;
        $missingTasks = array_diff(self::REQUIRED_TASKS, array_keys($this->tasks));
        if (empty($missingTasks)) {
            return;
        }

        $this->logger->critical(
            'LokiCheckout: Required checkout tasks are missing from '.self::class.': '
            .implode(', ', $missingTasks)
            .'. Probably a module replaces the "tasks" argument in an area-specific di.xml (like etc/frontend/di.xml).'
        );
    }
}
