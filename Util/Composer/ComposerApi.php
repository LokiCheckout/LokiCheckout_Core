<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Composer;

use Exception;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\CacheInterface;
use RuntimeException;
use Throwable;

class ComposerApi
{
    const PACKAGES_URL = 'https://composer.yireo.com/packages.json';

    const PROJECTS_URL = 'https://composer.yireo.com/projects.json';

    private const CACHE_PREFIX = 'loki_checkout_composer_api_';

    private const CACHE_LIFETIME = 3600;

    private const CONNECT_TIMEOUT = 3;

    private const TIMEOUT = 5;

    /** @var array<string, array> */
    private array $data = [];

    /** @var array<string, Throwable> */
    private array $failures = [];

    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly CacheInterface $cache,
    ) {
    }

    /**
     * @return array[]
     * @throws GuzzleException
     * @throws RuntimeException
     */
    public function getProjects(): array
    {
        return $this->fetch(self::PROJECTS_URL, 'projects');
    }

    /**
     * @return array[]
     * @throws GuzzleException
     * @throws RuntimeException
     */
    public function getPackages(): array
    {
        return $this->fetch(self::PACKAGES_URL, 'packages');
    }

    public function getAvailableVersionDate(string $composerName): string
    {
        $packages = $this->getPackages();
        $availableVersion = $this->getAvailableVersion($composerName);

        if (!isset($packages[$composerName][$availableVersion])) {
            return 'n/a '.$availableVersion;
        }

        $package = $packages[$composerName][$availableVersion];
        if (isset($package['time'])) {
            return date('Y-m-d H:i:s', strtotime($package['time']));
        }

        if (isset($package['release_date'])) {
            return date('Y-m-d H:i:s', strtotime($package['release_date']));
        }

        return 'n/a';
    }

    private function getPackage(string $composerName): array
    {
        $packages = $this->getPackages();
        if (!isset($packages[$composerName])) {
            throw new Exception('Package not found: ' . $composerName);
        }

        return $packages[$composerName];
    }

    private function getProject(string $composerName): array
    {
        $projects = $this->getProjects();
        foreach ($projects as $project) {
            if ($project['composer_name'] === $composerName) {
                return $project;
            }
        }

        throw new Exception('Project not found: ' . $composerName);
    }

    public function getAvailableVersion(string $composerName): string
    {
        try {
            $package = $this->getPackage($composerName);
            return $this->getAvailableVersionFromPackage($package);
        } catch (Exception $e) {
        }

        try {
            $project = $this->getProject($composerName);
            return $this->getAvailableVersionFromProject($project);
        } catch (Exception $e) {
        }

        return '';
    }

    private function getAvailableVersionFromProject(array $project): string
    {
        $versions = [];
        $devCommit = '';
        foreach (array_keys($project['versions'] ?? []) as $version) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', (string)$version, $match)) {
                $devCommit = $match[2];
                continue;
            }

            $versions[] = (string)$version;
        }

        if (empty($versions)) {
            return $devCommit;
        }

        usort($versions, 'version_compare');
        return array_pop($versions);
    }

    private function getAvailableVersionFromPackage(array $package): string
    {
        $versions = [];
        $devCommit = '';
        foreach ($package as $packageRelease) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', $packageRelease['version'], $match)) {
                $devCommit = $match[2];
                continue;
            }

            $versions[] = $packageRelease['version'];
        }

        if (empty($versions)) {
            return $devCommit;
        }

        usort($versions, 'version_compare');
        return array_pop($versions);
    }

    public function getLatestCommit(string $composerName): string
    {
        foreach ($this->getProjects() as $project) {
            if (($project['composer_name'] ?? '') !== $composerName) {
                continue;
            }

            return (string)($project['last_commit'] ?? '');
        }

        return '';
    }

    /**
     * Results and failures are kept for the rest of the request, so an unreachable API is only called once
     */
    private function fetch(string $url, string $key): array
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (isset($this->failures[$key])) {
            throw $this->failures[$key];
        }

        $cacheId = self::CACHE_PREFIX . $key;
        $cachedData = (string)$this->cache->load($cacheId);
        if ($cachedData !== '') {
            $data = json_decode($cachedData, true);
            if (is_array($data)) {
                return $this->data[$key] = $data;
            }
        }

        try {
            $client = $this->clientFactory->create(['config' => [
                'connect_timeout' => self::CONNECT_TIMEOUT,
                'timeout' => self::TIMEOUT,
            ]]);
            $data = json_decode((string)$client->get($url)->getBody(), true);
            if (!is_array($data) || !isset($data[$key]) || !is_array($data[$key])) {
                throw new RuntimeException('Invalid response from ' . $url);
            }
        } catch (Throwable $e) {
            $this->failures[$key] = $e;
            throw $e;
        }

        $this->cache->save((string)json_encode($data[$key]), $cacheId, [], self::CACHE_LIFETIME);

        return $this->data[$key] = $data[$key];
    }
}
