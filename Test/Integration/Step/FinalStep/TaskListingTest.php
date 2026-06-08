<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Task\SaveInAddressBookTask;
use LokiCheckout\Core\Step\FinalStep\Task\TaskInterface;
use LokiCheckout\Core\Step\FinalStep\TaskListing;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class TaskListingTest extends TestCase
{
    public function testDiConfiguredTasks(): void
    {
        $listing = ObjectManager::getInstance()->get(TaskListing::class);
        $tasks = $listing->getTasks();

        $this->assertNotEmpty($tasks);

        $hasSaveInAddressBook = false;
        foreach ($tasks as $task) {
            $this->assertInstanceOf(TaskInterface::class, $task);
            if ($task instanceof SaveInAddressBookTask) {
                $hasSaveInAddressBook = true;
            }
        }

        $this->assertTrue($hasSaveInAddressBook);
    }
}
