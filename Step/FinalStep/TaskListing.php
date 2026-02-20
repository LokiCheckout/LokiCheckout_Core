<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Task\TaskInterface;

class TaskListing
{
    public function __construct(
        private array $tasks = []
    ) {
    }

    /**
     * @return TaskInterface[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}
