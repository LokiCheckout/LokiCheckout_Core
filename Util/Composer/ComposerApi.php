<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Composer;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ComposerApi
{
    const PACKAGES_URL = 'https://composer.yireo.com/packages.json';

    const PROJECTS_URL = 'https://composer.yireo.com/projects.json';

    /**
     * @return array[]
     * @throws GuzzleException
     */
    public function getProjects(): array
    {
        static $projects = null;
        if (is_array($projects)) {
            return $projects;
        }

        $client = new Client();
        $response = $client->get(self::PROJECTS_URL);
        $data = json_decode($response->getBody()->getContents(), true);
        $projects = $data['projects'];
        return $projects;
    }

    /**
     * @return array[]
     * @throws GuzzleException
     */
    public function getPackages(): array
    {
        static $packages = null;
        if (is_array($packages)) {
            return $packages;
        }

        $client = new Client();
        $response = $client->get(self::PACKAGES_URL);
        $data = json_decode($response->getBody()->getContents(), true);
        $packages = $data['packages'];
        return $packages;
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
        foreach (array_keys($project['versions']) as $version) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', $version, $match)) {
                $devCommit = $match[2];
                continue;
            }

            $versions[] = $version;
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
            if ($project['composer_name'] !== $composerName) {
                continue;
            }

            return $project['last_commit'];
        }

        return '';
    }
}
