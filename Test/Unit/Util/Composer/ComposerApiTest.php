<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Util\Composer;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LokiCheckout\Core\Util\Composer\ComposerApi;
use Magento\Framework\App\CacheInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ComposerApiTest extends TestCase
{
    private const PACKAGES = [
        'loki-checkout/foo' => [
            '1.0.0' => ['version' => '1.0.0', 'time' => '2026-01-01T00:00:00+00:00'],
            '1.2.0' => ['version' => '1.2.0', 'time' => '2026-02-01T00:00:00+00:00'],
        ],
    ];

    private const PROJECTS = [
        ['composer_name' => 'loki-checkout/foo', 'last_commit' => 'abc123', 'versions' => ['1.2.0' => []]],
    ];

    public function testClientIsCreatedWithTimeouts(): void
    {
        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory->expects($this->once())
            ->method('create')
            ->with($this->callback(function (array $data): bool {
                $config = $data['config'] ?? [];
                return ($config['connect_timeout'] ?? 0) > 0 && ($config['timeout'] ?? 0) > 0;
            }))
            ->willReturn($this->createClient(['packages' => self::PACKAGES]));

        $composerApi = new ComposerApi($clientFactory, $this->createCache());
        $this->assertSame('1.2.0', $composerApi->getAvailableVersion('loki-checkout/foo'));
    }

    public function testResponsesAreOnlyFetchedOncePerRequest(): void
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with(ComposerApi::PACKAGES_URL)
            ->willReturn(new Response(200, [], json_encode(['packages' => self::PACKAGES])));

        $composerApi = new ComposerApi($this->createClientFactory($client), $this->createCache());
        $composerApi->getAvailableVersion('loki-checkout/foo');
        $composerApi->getAvailableVersionDate('loki-checkout/foo');
        $this->assertSame(self::PACKAGES, $composerApi->getPackages());
    }

    public function testFailureIsRememberedForTheRestOfTheRequest(): void
    {
        $exception = new ConnectException('Connection timed out', new Request('GET', ComposerApi::PROJECTS_URL));
        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('get')->willThrowException($exception);

        $composerApi = new ComposerApi($this->createClientFactory($client), $this->createCache());
        for ($i = 0; $i < 3; $i++) {
            try {
                $composerApi->getLatestCommit('loki-checkout/foo');
                $this->fail('Expected exception');
            } catch (ConnectException $e) {
                $this->assertSame($exception, $e);
            }
        }
    }

    public function testCachedResponseAvoidsHttpCall(): void
    {
        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory->expects($this->never())->method('create');

        $cache = $this->createStub(CacheInterface::class);
        $cache->method('load')->willReturnCallback(
            fn (string $identifier) => str_contains($identifier, 'projects') ? json_encode(self::PROJECTS) : false
        );

        $composerApi = new ComposerApi($clientFactory, $cache);
        $this->assertSame('abc123', $composerApi->getLatestCommit('loki-checkout/foo'));
    }

    public function testSuccessfulResponseIsCached(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('load')->willReturn(false);
        $cache->expects($this->once())
            ->method('save')
            ->with(json_encode(self::PROJECTS), $this->stringContains('projects'), $this->anything(), $this->greaterThan(0));

        $composerApi = new ComposerApi(
            $this->createClientFactory($this->createClient(['projects' => self::PROJECTS])),
            $cache
        );
        $this->assertSame(self::PROJECTS, $composerApi->getProjects());
    }

    public function testInvalidResponseThrowsRuntimeException(): void
    {
        $composerApi = new ComposerApi(
            $this->createClientFactory($this->createClient(['error' => 'maintenance'])),
            $this->createCache()
        );

        $this->expectException(RuntimeException::class);
        $composerApi->getProjects();
    }

    public function testLatestCommitWithoutCommitInProject(): void
    {
        $composerApi = new ComposerApi(
            $this->createClientFactory($this->createClient(['projects' => [['composer_name' => 'loki-checkout/foo']]])),
            $this->createCache()
        );

        $this->assertSame('', $composerApi->getLatestCommit('loki-checkout/foo'));
    }

    private function createClient(array $data): Client
    {
        $client = $this->createStub(Client::class);
        $client->method('get')->willReturn(new Response(200, [], (string)json_encode($data)));

        return $client;
    }

    private function createClientFactory(Client $client): ClientFactory
    {
        $clientFactory = $this->createStub(ClientFactory::class);
        $clientFactory->method('create')->willReturn($client);

        return $clientFactory;
    }

    private function createCache(): CacheInterface
    {
        $cache = $this->createStub(CacheInterface::class);
        $cache->method('load')->willReturn(false);

        return $cache;
    }
}
