<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Composer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ComposerApi
{
    const URL = 'https://composer.yireo.com/packages.json';

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
        $response = $client->get(self::URL);
        $data = json_decode($response->getBody()->getContents(), true);
        $packages = $data['packages'];
        return $packages;
    }

    public function getAvailableVersionDate(string $composerName): string
    {
        $packages = $this->getPackages();
        $availableVersion = $this->getAvailableVersion($composerName);

        if (!isset($packages[$composerName][$availableVersion])) {
            return 'n/a';
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

    public function getAvailableVersion(string $composerName): string
    {
        $packages = $this->getPackages();
        if (!isset($packages[$composerName])) {
            return '';
        }

        $versions = [];
        $devCommit = '';
        foreach ($packages[$composerName] as $package) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', $package['version'], $match)) {
                $devCommit = $match[2];
                continue;
            }

            $versions[] = $package['version'];
        }

        if ($versions === []) {
            return $devCommit;
        }

        usort($versions, 'version_compare');
        return array_pop($versions);
    }

    public function getLatestCommit(string $composerName): string
    {
        $packages = $this->getPackages();
        if (!isset($packages[$composerName])) {
            return '';
        }

        foreach ($packages[$composerName] as $package) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', $package['version'], $match)) {
                return $match[2];
            }
        }

        return '';
    }
}
