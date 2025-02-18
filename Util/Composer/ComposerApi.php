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

    public function getAvailableVersion(string $composerName): string
    {
        $packages = $this->getPackages();
        if (!isset($packages[$composerName])) {
            return 'unknown';
        }

        $versions = [];
        $devCommit = '';
        foreach ($packages[$composerName] as $package) {
            if (preg_match('/^dev-([a-zA-Z0-9]+)#(.*)/', $package['version'], $match)) {
                $devCommit = substr($match[2], strlen($match[2]) - 8, 8);
                continue;
            }

            $versions[] = $package['version'];
        }

        if (empty($versions)) {
            return $devCommit;
        }

        usort($versions, 'version_compare');
        return array_pop($versions);
    }
}
