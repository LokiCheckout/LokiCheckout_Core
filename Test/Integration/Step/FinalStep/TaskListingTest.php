<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Task\AgreementsTask;
use LokiCheckout\Core\Step\FinalStep\Task\NewsletterTask;
use LokiCheckout\Core\Step\FinalStep\Task\RecaptchaTask;
use LokiCheckout\Core\Step\FinalStep\Task\SaveInAddressBookTask;
use LokiCheckout\Core\Step\FinalStep\Task\TaskInterface;
use LokiCheckout\Core\Step\FinalStep\TaskListing;
use LokiCheckout\Core\Test\Integration\Stub\SpyLogger;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ReflectionProperty;

class TaskListingTest extends TestCase
{
    private const CORE_TASKS = [
        'agreements' => AgreementsTask::class,
        'recaptcha' => RecaptchaTask::class,
        'saveInAddressBook' => SaveInAddressBookTask::class,
        'newsletter' => NewsletterTask::class,
    ];

    #[AppArea('frontend')]
    public function testFrontendAreaContainsCoreTasks(): void
    {
        $this->assertSame(Area::AREA_FRONTEND, $this->getObjectManager()->get(State::class)->getAreaCode());

        $taskListing = $this->getObjectManager()->create(TaskListing::class);
        $this->assertInstanceOf(
            LoggerInterface::class,
            (new ReflectionProperty(TaskListing::class, 'logger'))->getValue($taskListing)
        );

        $tasks = $taskListing->getTasks();
        foreach (self::CORE_TASKS as $taskName => $taskClass) {
            $this->assertArrayHasKey($taskName, $tasks, 'Core task "'.$taskName.'" is missing in the frontend area');
            $this->assertInstanceOf($taskClass, $tasks[$taskName]);
        }
    }

    public function testRequiredTasksMatchCoreTasks(): void
    {
        $this->assertSame(array_keys(self::CORE_TASKS), TaskListing::REQUIRED_TASKS);
    }

    #[AppArea('frontend')]
    public function testMissingRequiredTasksAreLoggedOnce(): void
    {
        $logger = new SpyLogger();
        $tasks = [
            'agreements' => $this->createStub(TaskInterface::class),
            'example' => $this->createStub(TaskInterface::class),
        ];

        $taskListing = $this->getObjectManager()->create(TaskListing::class, [
            'tasks' => $tasks,
            'logger' => $logger,
        ]);

        $this->assertSame($tasks, $taskListing->getTasks());
        $this->assertSame($tasks, $taskListing->getTasks());

        $this->assertCount(1, $logger->records);
        $this->assertSame(LogLevel::CRITICAL, $logger->records[0]['level']);
        $this->assertStringContainsString('recaptcha, saveInAddressBook, newsletter', $logger->records[0]['message']);
        $this->assertStringNotContainsString('agreements', $logger->records[0]['message']);
    }

    public function testNothingIsLoggedWhenAllRequiredTasksArePresent(): void
    {
        $logger = new SpyLogger();
        $tasks = [];
        foreach (TaskListing::REQUIRED_TASKS as $taskName) {
            $tasks[$taskName] = $this->createStub(TaskInterface::class);
        }

        $taskListing = new TaskListing($tasks, $logger);

        $this->assertSame($tasks, $taskListing->getTasks());
        $this->assertSame([], $logger->records);
    }

    public function testManuallyConstructedListingWithoutLoggerKeepsWorking(): void
    {
        $tasks = ['example' => $this->createStub(TaskInterface::class)];

        $this->assertSame($tasks, (new TaskListing($tasks))->getTasks());
    }

    private function getObjectManager(): ObjectManagerInterface
    {
        return Bootstrap::getObjectManager();
    }
}
